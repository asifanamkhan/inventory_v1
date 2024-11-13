<?php

namespace App\Livewire\Dashboard\Sale;

use App\Service\Payment;
use App\Service\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleReturnForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $sale_id;
    public $document = [];
    public $paymentState = [];
    public $productsearch, $payment_methods, $ref_memo_no, $customer_id;
    public $resultProducts = [];
    public $saleCart = [];
    public $saleCheck = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $pay_amt, $due, $action;


    public function mount($sale_id = null)
    {
        // dd($sale_id);
        if ($sale_id) {
            $this->sale_id = $sale_id;
            $tran_mst = DB::table('sale')
                ->where('id', $sale_id)
                ->first();
            $sale_rt = DB::table('sale_return')
                ->where('ref_memo_no', $tran_mst->memo_no)
                ->first();
            // dd($tran_mst);
            $this->state['net_total'] = 0;
            $this->state['ref_memo_no'] =  $tran_mst->memo_no;
            $this->state['total'] = @$sale_rt->total ?? 0;;
            $this->state['qty'] = @$sale_rt->qty ?? 0;
            $this->state['shipping'] = 0;
            $this->state['status'] = $tran_mst->status;
            $this->state['customer_id'] = $tran_mst->customer_id;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['date'] = Carbon::parse($tran_mst->date)->toDateString();

            $resultDtls = DB::table('vw_product_tran_dtl as p')
                ->where('type','sl')
                ->where('p.ref_id', $sale_id)
                ->get([
                    'p.product_id',
                    'p.quantity',
                    'p.product_name as name',
                    'p.variant_description',
                    'p.rate',
                    'p.lot_ref_memo'
                ]);


            foreach ($resultDtls as $resultDtl) {
                $qty = DB::table('product_tran_dtl')
                    ->where('product_id', $resultDtl->product_id)
                    ->where('type' ,'slrt')
                    ->where('ref_id', $sale_id)
                    ->first();

                $this->saleCart[] = [
                    'name' => $resultDtl->name,
                    'variant_description' => $resultDtl->variant_description,
                    'sale_price' => $resultDtl->rate,
                    'line_total' => 0,
                    'qty' => $resultDtl->quantity,
                    'product_id' => $resultDtl->product_id,
                    'return_qty' => $qty->quantity ?? 0,
                    'is_check' => 0,
                    'lot_ref_memo' => @$resultDtl->lot_ref_memo,
                ];

                $this->saleCheck[] = $resultDtl->product_id;
            }

            $this->state['date'] = Carbon::now()->toDateString();

            // dd($tran_mst->tran_date);
        }
    }



    public function saleActive($key)
    {
        if ($this->saleCart[$key]['is_check'] == true) {
            $this->saleCart[$key]['is_check'] = 1;
        } else {
            $this->saleCart[$key]['is_check'] = 0;
            $this->saleCart[$key]['return_qty'] =  '';
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
        unset($this->saleCart[$key]);
        $del_key = array_search($id, $this->saleCheck);
        unset($this->saleCheck[$del_key]);
        $this->grandCalculation();
    }

    public function calculation($key)
    {
        $qty = (float)$this->saleCart[$key]['return_qty'] ?? 0;
        if($qty > $this->saleCart[$key]['qty']){
            session()->flash('error', 'Return qty cant bigger than sale qty');
            $qty = $this->saleCart[$key]['qty'];
            $this->saleCart[$key]['return_qty'] = $qty;
        }
        $mrp_rate = (float)$this->saleCart[$key]['sale_price'] ?? 0;
        $this->saleCart[$key]['line_total'] = ($qty * $mrp_rate);
        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $shipping = $this->state['shipping'] ?? 0;
        $discount = $this->state['discount'] ?? 0;

        foreach ($this->saleCart as $value) {

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

        if (count($this->saleCart) > 0) {

            // dd(
            //     $this->state,
            //     $this->paymentState,
            //     $this->saleCart,
            // );

            $this->state['created_by'] = Auth::user()->id;
            $this->state['branch_id'] = 1;
            $this->state['due'] = $this->due ?? 0;
            $this->state['paid'] = $this->pay_amt ?? 0;
            $this->state['payment_status'] = Payment::PaymentCheck($this->due);


            DB::beginTransaction();
            try {

                $sale = DB::table('sale_return')
                    ->where('ref_memo_no', $this->state['ref_memo_no'])
                    ->first();

                if ($sale) {

                    DB::table('sale_return')
                        ->where('ref_memo_no', $this->state['ref_memo_no'])
                        ->update($this->state);
                    DB::table('product_tran_dtl')
                        ->where('type', 'slrt')
                        ->where('ref_id', $sale->id)
                        ->delete();

                    DB::table('voucher')
                        ->where('tran_type', 'slrt')
                        ->where('voucher_type', 'DR')
                        ->where('return_ref_memo', $sale->memo_no)
                        ->update([
                            'amount' => $this->state['total'],
                        ]);
                } else {
                    $tran_id = DB::table('sale_return')
                        ->insertGetId($this->state);

                    $sale = DB::table('sale_return')
                        ->where('id', $tran_id)
                        ->first();

                    DB::table('voucher')->insert([
                        'date' => $this->state['date'],
                        'voucher_type' => 'DR',
                        'tran_type' => 'slrt',
                        'description' => 'Initial voucher for ' . $sale->memo_no,
                        'amount' => $this->state['total'],
                        'created_by' => Auth::user()->id,
                        'ref_id' => $tran_id,
                        'tran_user_id' => $this->customer_id,
                        'ref_memo' => $this->state['ref_memo_no'],
                        'return_ref_memo' => $sale->memo_no,

                    ]);
                }


                foreach ($this->saleCart as $key => $value) {
                    DB::table('product_tran_dtl')->insert([
                        'branch_id' => 1,
                        'product_id' => $value['product_id'],
                        'quantity' => $value['return_qty'],
                        'rate' => $value['sale_price'],
                        'total' => $value['line_total'],
                        'created_by' => Auth::user()->id,
                        'ref_id' => @$tran_id ?? $sale->id,
                        'ref_memo' => $this->state['ref_memo_no'],
                        'return_ref_memo' => $sale->memo_no,
                        'type' => 'slrt',
                        'tran_user_id' => $this->customer_id,
                        'lot_ref_memo' => $value['lot_ref_memo'],
                    ]);
                }





                DB::commit();

                session()->flash('status', 'Sale returnd successfully');
                return $this->redirect(route('sale-return'), navigate: true);
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.sale.sale-return-form');
    }
}