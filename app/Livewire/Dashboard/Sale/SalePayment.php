<?php

namespace App\Livewire\Dashboard\Sale;

use App\Service\PaymentMethod;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SalePayment extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $payment_methods, $mst;
    public $sale_id;
    public $paymentState = [];
    public function paymentMethodAll()
    {
        return $this->payment_methods = PaymentMethod::$methods;
    }

    #[On('sale-payment')]
    public function renderNew($id){
        $this->sale_id = $id;
        $this->saleMst($id);
    }
    public function saleMst($id)
    {
        $this->mst = (array)DB::table('vw_sale as p')
            ->where('p.sale_id', $id)
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
            ->where('tran_type', 'sl')
            ->where('cash_type', '!=', null)
            ->where('p.ref_id', $this->sale_id)
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
                    'voucher_type' => 'DR',
                    'tran_type' => 'sl',
                    'description' => $this->paymentState['description'] ?? '',
                    'tran_no' => $this->paymentState['tran_no'] ?? '',
                    'amount' => $this->paymentState['amount'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->sale_id,
                    'tran_user_id' => $this->mst['customer_id'],
                    'ref_memo' => $this->mst['memo_no'],
                    'cash_type' => 'IN',
                    'pay_mode' => $this->paymentState['pay_mode'],

                ]);

                DB::commit();

                session()->flash('status', 'New Payment made successfully');
                $this->dispatch('sale-all');
                $this->paymentState['amount'] = 0;
                $this->saleMst($this->sale_id);
                $this->paymentState['pay_mode'] = 1;
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard.sale.sale-payment');
    }
}