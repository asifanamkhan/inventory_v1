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


    public function paymentMethodAll()
    {
        return $this->payment_methods = PaymentMethod::$methods;
    }

    public function updatedProductsearch()
    {
        if ($this->productsearch) {

            $result = DB::table('vw_purchase as p')
                ->where('memo_no', $this->productsearch)
                ->get()
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);
            } else {

                $this->resultProducts = DB::table('vw_purchase as p')
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->orWhere('p.supplier_name', 'like', '%' . $this->productsearch . '%')
                    ->get()
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
            $this->resetProductSearch();
        }

        $this->countProduct = count($this->resultProducts);
    }

    public function mount($purchase_id = null)
    {

        if ($purchase_id) {
            $this->purchase_id = $purchase_id;
            $tran_mst = DB::table('purchase')
                ->where('id', $purchase_id)
                ->first();
            // dd($tran_mst);
            $this->state['net_total'] = $tran_mst->net_total;
            $this->state['total'] = $tran_mst->total;
            $this->state['qty'] = $tran_mst->qty;
            $this->state['shipping'] = $tran_mst->shipping;
            $this->state['status'] = $tran_mst->status;
            $this->state['supplier_id'] = $tran_mst->supplier_id;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['date'] = Carbon::parse($tran_mst->date)->toDateString();

            $this->pay_amt = $tran_mst->paid;
            $this->due = $tran_mst->due;

            $this->edit_select['supplier_id'] = $tran_mst->supplier_id;

            $resultPay = DB::table('voucher')
                ->where('tran_type', 'pr')
                ->where('cash_type', '!=', '')
                ->where('ref_id', $purchase_id)
                ->first();

            if ($resultPay) {
                $this->paymentState['pay_mode'] = $resultPay->pay_mode;

                if ($resultPay->pay_mode != 1) {
                    $this->paymentState['description'] = $resultPay->description;
                    $this->paymentState['tran_no'] = $resultPay->tran_no;
                }
            } else {
                $this->paymentState['pay_mode'] = 1;
            }


            // dd($resultPay);

            $resultDtls = DB::table('product_tran_dtl as p')
                ->where('type', 'pr')
                ->where('p.ref_id', $purchase_id)
                ->leftJoin('vw_product_info as pr', function ($join) {
                    $join->on('pr.product_id', '=', 'p.product_id');
                })
                ->get([
                    'p.rate',
                    'p.product_id',
                    'p.total',
                    'p.quantity',
                    'pr.name',
                    'pr.variant_description',
                ]);

            // dd($resultDtls);

            foreach ($resultDtls as $resultDtl) {
                $this->purchaseCart[] = [
                    'name' => $resultDtl->name,
                    'variant_description' => $resultDtl->variant_description,
                    'purchase_price' => $resultDtl->rate,
                    'line_total' => $resultDtl->total,
                    'qty' => $resultDtl->quantity,
                    'product_id' => $resultDtl->product_id,
                ];

                $this->purchaseCheck[] = $resultDtl->product_id;
            }


            // dd($tran_mst->tran_date);
        } else {
            $this->state['net_total'] = 0;
            $this->state['total'] = 0;
            $this->state['qty'] = 0;
            $this->state['status'] = 1;
            $this->state['date'] = Carbon::now()->toDateString();
            $this->paymentState['pay_mode'] = 1;
        }

        $this->paymentMethodAll();
    }

    //search increment decrement start
    public function decrementHighlight()
    {
        if ($this->searchSelect > 0) {
            $this->searchSelect--;
        }
    }
    public function incrementHighlight()
    {
        if ($this->searchSelect < ($this->countProduct - 1)) {
            $this->searchSelect++;
        }
    }
    public function selectAccount()
    {
        $this->resultAppend($this->searchSelect);
    }

    public function searchRowSelect($pk)
    {
        $this->resultAppend($pk);
    }

    public function resultAppend($key)
    {
        $search = @$this->resultProducts[$key]->purchase_id;
        $this->ref_memo_no = @$this->resultProducts[$key]->memo_no;
        $this->supplier_id = @$this->resultProducts[$key]->supplier_id;
        if ($search) {
            $this->purchaseCart = [];

            $tran_dtls = DB::table('vw_product_tran_dtl as p')
                ->where('type', 'pr')
                ->where('p.ref_id', $search)
                ->get();

            foreach ($tran_dtls as $tran_dtl) {
                $this->purchaseCart[] = [
                'name' => $tran_dtl->product_name,
                'variant_description' => $tran_dtl->variant_description,
                'purchase_price' => $tran_dtl->rate,
                'line_total' => 0,
                'qty' => $tran_dtl->quantity,
                'return_qty' => 0,
                'product_id' => $tran_dtl->product_id,
                'is_check' => 0,
                ];
            }

            $this->grandCalculation();

            $this->productsearch = '';
            $this->resetProductSearch();
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

    public function hideDropdown()
    {
        $this->resetProductSearch();
    }

    //search increment decrement end

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

        ])->validate();

        if (count($this->purchaseCart) > 0) {

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

            $this->dispatch($this->action, [
                'state' => $this->state,
                'purchaseCart' => $this->purchaseCart,
                'pay_amt' => $this->pay_amt,
                'paymentState' => $this->paymentState,
                'ref_memo_no' => $this->ref_memo_no,
                'supplier_id' => $this->supplier_id,
            ]);
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-return-form');
    }
}