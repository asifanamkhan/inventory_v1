<?php

namespace App\Livewire\Dashboard\Purchase;

use App\Service\PaymentMethod;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PurchasePayment extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $payment_methods, $mst;
    public $purchase_id;
    public $paymentState = [];
    public function paymentMethodAll()
    {
        return $this->payment_methods = PaymentMethod::$methods;
    }

    #[On('purchase-payment')]
    public function renderNew($id){
        $this->purchase_id = $id;
        $this->purchaseMst($id);
    }
    public function purchaseMst($id)
    {
        $this->mst = (array)DB::table('vw_purchase as p')
            ->where('p.purchase_id', $id)
            ->first(['p.*']);
    }
    public function mount()
    {
        $this->paymentState['pay_mode'] = 1;
        $this->paymentState['amount'] = 0;
        $this->paymentMethodAll();
    }
    #[Computed]
    public function resultPayments()
    {

        $payments = DB::table('voucher as p')
            ->where('tran_type', 'pr')
            ->where('cash_type', '!=', null)
            ->where('p.ref_id', $this->purchase_id)
            ->orderBy('p.voucher_no', 'DESC')
            ->select(['p.*',]);

        if ($this->search) {
            $payments
                ->orwhere(DB::raw('lower(p.voucher_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.amount', 'like', '%' . $this->search . '%');
        }

        return $payments->paginate($this->pagination);
    }

    public function save()
    {

        Validator::make($this->paymentState, [
            'amount' => 'required',
            'pay_mode' => 'required',

        ])->validate();

        if ((float)$this->paymentState['amount'] == 0 || (float)$this->paymentState['amount'] > (float)$this->mst['total']) {
            session()->flash('error', 'Payment amount is incorrect');
        } else {
            DB::beginTransaction();
            try {

                DB::table('voucher')->insert([
                    'date' => Carbon::now()->toDateString(),
                    'voucher_type' => 'CR',
                    'tran_type' => 'pr',
                    'description' => $this->paymentState['description'] ?? '',
                    'tran_no' => $this->paymentState['tran_no'] ?? '',
                    'amount' => $this->paymentState['amount'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->purchase_id,
                    'tran_user_id' => $this->mst['supplier_id'],
                    'ref_memo' => $this->mst['memo_no'],
                    'cash_type' => 'OUT',
                    'pay_mode' => $this->paymentState['pay_mode'],

                ]);

                DB::commit();

                session()->flash('status', 'New Payment made successfully');
                $this->dispatch('purchase-all');
                $this->paymentState['amount'] = 0;
                $this->purchaseMst($this->purchase_id);
                $this->paymentState['pay_mode'] = 1;
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-payment');
    }
}