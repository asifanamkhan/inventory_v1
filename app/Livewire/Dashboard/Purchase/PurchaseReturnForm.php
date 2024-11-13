<?php

namespace App\Livewire\Dashboard\Purchase;

use App\Service\Payment;
use App\Service\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $purchase_id;
    public $document = [];
    public $paymentState = [];
    public $productsearch, $payment_methods, $ref_memo_no, $supplier_id;
    public $resultProducts = [];
    public $purchaseCart = [];
    public $purchaseCheck = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $pay_amt, $due, $action;


    public function mount($purchase_id = null)
    {
        // dd($purchase_id);
        if ($purchase_id) {
            $this->purchase_id = $purchase_id;
            $tran_mst = DB::table('purchase')
                ->where('id', $purchase_id)
                ->first();
            $purchase_rt = DB::table('purchase_return')
                ->where('ref_memo_no', $tran_mst->memo_no)
                ->first();

            $this->state['net_total'] = 0;
            $this->state['ref_memo_no'] =  $tran_mst->memo_no;
            $this->state['total'] = @$purchase_rt->total ?? 0;;
            $this->state['qty'] = @$purchase_rt->qty ?? 0;
            $this->state['shipping'] = 0;
            $this->state['status'] = $tran_mst->status;
            $this->state['supplier_id'] = $tran_mst->supplier_id;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['date'] = Carbon::parse($tran_mst->date)->toDateString();
            $this->supplier_id = $this->state['supplier_id'];

            $resultDtls = DB::table('vw_product_stock_by_purchase as p')
                ->where('p.memo_no', $tran_mst->memo_no)
                ->get([
                    'p.product_id',
                    'p.purchase_qty as quantity',
                    'p.name',
                    'p.variant_description',
                    'p.pr_return_qty',
                ]);



            foreach ($resultDtls as $resultDtl) {
                $rate = DB::table('product_tran_dtl')
                    ->where('product_id', $resultDtl->product_id)
                    ->where('type', 'pr')
                    ->where('ref_id', $purchase_id)
                    ->first();

                $this->purchaseCart[] = [
                    'name' => $resultDtl->name,
                    'variant_description' => $resultDtl->variant_description,
                    'purchase_price' => $rate->rate,
                    'line_total' => 0,
                    'qty' => $resultDtl->quantity,
                    'product_id' => $resultDtl->product_id,
                    'return_qty' => $resultDtl->pr_return_qty,
                    'is_check' => 0,
                ];

                $this->purchaseCheck[] = $resultDtl->product_id;
            }

            $this->state['date'] = Carbon::now()->toDateString();

            // dd($tran_mst->tran_date);
        }
    }



    public function purchaseActive($key)
    {
        if ($this->purchaseCart[$key]['is_check'] == true) {
            $this->purchaseCart[$key]['is_check'] = 1;
        } else {
            $this->purchaseCart[$key]['is_check'] = 0;
            $this->purchaseCart[$key]['return_qty'] =  '';
        }
        $this->calculation($key);
    }


    public function resetProductSearch()
    {
        $this->searchSelect = -1;
        $this->resultProducts = [];
    }

    public function removeItem($key, $id)
    {
        unset($this->purchaseCart[$key]);
        $del_key = array_search($id, $this->purchaseCheck);
        unset($this->purchaseCheck[$del_key]);
        $this->grandCalculation();
    }

    public function calculation($key)
    {
        $qty = (float)$this->purchaseCart[$key]['return_qty'] ?? 0;
        if ($qty > $this->purchaseCart[$key]['qty']) {
            session()->flash('error', 'Return qty cant bigger than purchase qty');
            $qty = $this->purchaseCart[$key]['qty'];
            $this->purchaseCart[$key]['return_qty'] = $qty;
        }
        $mrp_rate = (float)$this->purchaseCart[$key]['purchase_price'] ?? 0;
        $this->purchaseCart[$key]['line_total'] = ($qty * $mrp_rate);
        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $shipping = $this->state['shipping'] ?? 0;
        $discount = $this->state['discount'] ?? 0;

        foreach ($this->purchaseCart as $value) {

            $sub_total += (float)$value['line_total'] ?? 0;
            $total_qty += (float)$value['return_qty'] ?? 0;
        }

        $this->state['net_total'] = number_format($sub_total, 2, '.', '') ?? 0;

        $this->state['qty'] = $total_qty ?? 0;

        $total = (float)$shipping + (float)$sub_total - (float)$discount;
        $this->state['total'] = number_format($total, 2, '.', '');

        if ($this->pay_amt <= $this->state['total']) {
            $this->due = number_format(((float)$this->state['total'] - (float)$this->pay_amt), 2, '.', '');
        } else {
            $this->pay_amt = $this->state['total'];
            $this->due = 0;
            session()->flash('payment-error', 'Payment amt cant bigger than net amount');
        }
    }

    public function save()
    {

        Validator::make($this->state, [
            'date' => 'required|date',
            'status' => 'required|numeric',
            'total' => 'required|numeric',
            'net_total' => 'required|numeric',
            'return_qty.*' => 'required|numeric|min:1',

        ])->validate();

        if (count($this->purchaseCart) > 0) {
            session()->flash('error', '*At least one product need to added');
            return false;
        }

        // dd(
        //     $this->state,
        //     $this->paymentState,
        //     $this->purchaseCart,
        // );

        $this->state['created_by'] = Auth::user()->id;
        $this->state['branch_id'] = 1;
        $this->state['due'] = $this->due ?? 0;
        $this->state['paid'] = $this->pay_amt ?? 0;
        $this->state['payment_status'] = Payment::PaymentCheck($this->due);



        $purchase_return = DB::table('purchase_return')
            ->where('ref_memo_no', $this->state['ref_memo_no'])
            ->first();

        if ($purchase_return) {
            $this->udate($purchase_return);
        } else {
            $this->create();
        }



    }

    public function update($purchase_return)
    {
        DB::beginTransaction();
        try {

            DB::table('purchase_return')
                ->where('ref_memo_no', $this->state['ref_memo_no'])
                ->update($this->state);

            DB::table('product_tran_dtl')
                ->where('type', 'prt')
                ->where('ref_id', $this->purchase_id)
                ->delete();

            DB::table('voucher')
                ->where('tran_type', 'prt')
                ->where('voucher_type', 'CR')
                ->where('ref_memo', $this->state['ref_memo_no'])
                ->update([
                    'amount' => $this->state['total'],
                ]);

            foreach ($this->purchaseCart as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['return_qty'],
                    'rate' => $value['purchase_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->purchase_id,
                    'ref_memo' => $this->state['ref_memo_no'],
                    'return_ref_memo' => $purchase_return->memo_no,
                    'type' => 'prt',
                    'tran_user_id' => $this->supplier_id,
                ]);
            }


            DB::commit();
            session()->flash('status', 'Purchase returnd successfully');
            return $this->redirect(route('purchase'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }

    public function create()
    {
        DB::beginTransaction();
        try {
            $tran_id = DB::table('purchase_return')
                ->insertGetId($this->state);

            $purchase = DB::table('purchase_return')
                ->where('id', $tran_id)
                ->first();

            DB::table('voucher')->insert([
                'date' => $this->state['date'],
                'voucher_type' => 'CR',
                'tran_type' => 'prt',
                'description' => 'Initial return voucher for ' . $this->state['ref_memo_no'],
                'amount' => $this->state['total'],
                'created_by' => Auth::user()->id,
                'ref_id' => $this->purchase_id,
                'tran_user_id' => $this->supplier_id,
                'ref_memo' => $this->state['ref_memo_no'],
                'return_ref_memo' => $purchase->memo_no,

            ]);

            foreach ($this->purchaseCart as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['return_qty'],
                    'rate' => $value['purchase_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->purchase_id,
                    'ref_memo' => $this->state['ref_memo_no'],
                    'return_ref_memo' => $purchase->memo_no,
                    'type' => 'prt',
                    'tran_user_id' => $this->supplier_id,
                ]);
            }

            DB::commit();
            session()->flash('status', 'Purchase returnd successfully');
            return $this->redirect(route('purchase'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-return-form');
    }
}