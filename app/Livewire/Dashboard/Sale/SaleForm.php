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

class SaleForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $sale_id;
    public $document = [];
    public $paymentState = [];
    public $customers, $productsearch, $psearch, $payment_methods;
    public $resultProducts = [];
    public $resultPurchase = [];
    public $saleCart = [];
    public $saleCheck = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $pay_amt, $due, $action, $purchase_memo_no;


    public function customersAll()
    {
        return $this->customers = DB::table('customers')
            ->orderBy('id', 'DESC')
            ->get();
    }


    public function paymentMethodAll()
    {
        return $this->payment_methods = PaymentMethod::$methods;
    }

    public function saleTypeChange(){
        $this->resultPurchase = [];
        $this->saleCart = [];
        $this->saleCheck = [];
        $this->purchase_memo_no = '';
        $this->psearch = '';
 
    }

    public function updatedPsearch()
    {

        $result = DB::table('vw_purchase as p')
            ->where('memo_no', $this->psearch)
            ->get()
            ->toArray();

        if ($result) {
            $this->purchase_memo_no = $this->psearch;
            $this->resultPurchase = [];
            $this->saleCart = [];
            $this->saleCheck = [];
        } else {
            $this->resultPurchase = DB::table('vw_purchase as p')
                ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->productsearch) . '%')
                ->orWhere('p.supplier_name', 'like', '%' . $this->productsearch . '%')
                ->get()
                ->toArray();
        }
    }

    public function prSearchRowSelect($key)
    {
        $this->purchase_memo_no = $this->resultPurchase[$key]->memo_no;
        $this->psearch = $this->resultPurchase[$key]->memo_no;
        $this->saleCart = [];
        $this->saleCheck = [];
        $this->resultPurchase = [];

    }

    public function updatedProductsearch()
    {
        if ($this->productsearch) {
            if ($this->state['sale_type'] == 1) {
                $this->productWiseSearch();
            } else {
                $this->lotWiseSearch();
            }
        } else {
            $this->resetProductSearch();
        }

        $this->countProduct = count($this->resultProducts);
    }

    public function mount($sale_id = null)
    {
        if ($sale_id) {
            $this->sale_id = $sale_id;
            $tran_mst = DB::table('sale')
                ->where('id', $sale_id)
                ->first();
            // dd($tran_mst);
            $this->state['net_total'] = $tran_mst->net_total;
            $this->state['total'] = $tran_mst->total;
            $this->state['qty'] = $tran_mst->qty;
            $this->state['shipping'] = $tran_mst->shipping;
            $this->state['status'] = $tran_mst->status;
            $this->state['customer_id'] = $tran_mst->customer_id;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['date'] = Carbon::parse($tran_mst->date)->toDateString();

            $this->pay_amt = $tran_mst->paid;
            $this->due = $tran_mst->due;

            $this->edit_select['customer_id'] = $tran_mst->customer_id;

            $resultPay = DB::table('voucher')
                ->where('tran_type', 'sl')
                ->where('cash_type', '!=', '')
                ->where('ref_id', $sale_id)
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
                ->where('type', 'sl')
                ->where('p.ref_id', $sale_id)
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
                $this->saleCart[] = [
                    'name' => $resultDtl->name,
                    'variant_description' => $resultDtl->variant_description,
                    'sale_price' => $resultDtl->rate,
                    'line_total' => $resultDtl->total,
                    'qty' => $resultDtl->quantity,
                    'product_id' => $resultDtl->product_id,
                ];

                $this->saleCheck[] = $resultDtl->product_id;
            }


            // dd($tran_mst->tran_date);
        } else {
            $this->state['net_total'] = 0;
            $this->state['total'] = 0;
            $this->state['qty'] = 0;
            $this->state['status'] = 1;
            $this->state['sale_type'] = 2;
            $this->state['date'] = Carbon::now()->toDateString();
            $this->paymentState['pay_mode'] = 1;
        }


        $this->customersAll();
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
        // dd($this->resultProducts);
        $search = @$this->resultProducts[$key]->product_id;

        if (!$search) {
            return 0;
        }

        $stock = $this->resultProducts[$key]->stock;

        if ($stock <= 0) {
            session()->flash('error', 'Product has no stock');
            return 0;
        }

        $valid = in_array($search, $this->saleCheck);
        if ($valid) {
            $this->resetProductSearch();
            session()->flash('error', 'Product already added to cart');
            return 0;
        }

        $this->saleCheck[] = $search;
        $pricing = $this->resultProducts[$key];
        $line_total = (float)$pricing->sale_price;

        $this->saleCart[] = [
            'name' => $pricing->name,
            'variant_description' => $pricing->variant_description,
            'sale_price' => $pricing->sale_price,
            'line_total' => $line_total,
            'qty' => 1,
            'product_id' => $search,
            'stock' => $stock,
        ];

        $this->grandCalculation();
        $this->productsearch = '';
        $this->resetProductSearch();
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
        unset($this->saleCart[$key]);
        $del_key = array_search($id, $this->saleCheck);
        unset($this->saleCheck[$del_key]);
        $this->grandCalculation();
    }

    public function calculation($key)
    {

        $stock = (float)$this->saleCart[$key]['stock'] ?? 0;
        if ((float)$this->saleCart[$key]['qty'] > $stock) {
            session()->flash('error', "Product has only $stock stock available");
            (float)$this->saleCart[$key]['qty'] = $stock;
        }
        $qty = (float)$this->saleCart[$key]['qty'] ?? 0;
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
            $total_qty += (float)$value['qty'] ?? 0;
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
            'customer_id' => 'required|numeric',
            'total' => 'required|numeric',
            'net_total' => 'required|numeric',

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

            $this->dispatch($this->action, [
                'state' => $this->state,
                'saleCart' => $this->saleCart,
                'pay_amt' => $this->pay_amt,
                'paymentState' => $this->paymentState,
                'purchase_memo_no' => $this->purchase_memo_no,
            ]);
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }

    public function productWiseSearch()
    {
        $result = DB::table('vw_product_info as p')
            ->where('barcode', $this->productsearch)
            ->get()
            ->toArray();

        if ($result) {
            $this->resultProducts = $result;
            $this->resultAppend(0);
        } else {

            $this->resultProducts = DB::table('vw_product_info as p')
                ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                ->orWhere('p.code', 'like', '%' . $this->productsearch . '%')
                ->orWhere('p.barcode', 'like', '%' . $this->productsearch . '%')
                ->get()
                ->toArray();
        }

        $this->searchSelect = -1;
    }

    public function lotWiseSearch()
    {
        if ($this->purchase_memo_no) {
            $result = DB::table('vw_product_stock_by_purchase as p')
                ->where('p.memo_no', $this->purchase_memo_no)
                ->where('p.barcode', $this->productsearch)
                ->get(['p.current_stock as stock', 'p.*'])
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);
            } else {
                // dd($this->purchase_memo_no);
                $this->resultProducts = DB::table('vw_product_stock_by_purchase as p')
                    ->where('p.memo_no', $this->purchase_memo_no)
                    ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->get(['p.current_stock as stock', 'p.*'])
                    ->toArray();
            }

            $this->searchSelect = -1;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.sale.sale-form');
    }
}