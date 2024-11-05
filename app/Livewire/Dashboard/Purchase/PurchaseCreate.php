<?php

namespace App\Livewire\Dashboard\Purchase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class PurchaseCreate extends Component
{

    #[On('savePurchase')]
    public function savePurchase($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {

            $tran_id = DB::table('purchase')
                ->insertGetId($formData['state']);


            $purchase = DB::table('purchase')
                ->where('id', $tran_id)
                ->first();


            foreach ($formData['purchaseCart'] as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['qty'],
                    'rate' => $value['purchase_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $tran_id,
                    'ref_memo' => $purchase->memo_no,
                    'type' => 'pr',
                    'tran_user_id' => $formData['state']['supplier_id'],
                ]);
            }


            DB::table('voucher')->insert([
                'date' => $formData['state']['date'],
                'voucher_type' => 'DR',
                'tran_type' => 'pr',
                'description' => 'Initial voucher for '.$purchase->memo_no,
                'amount' => $formData['state']['total'],
                'created_by' => Auth::user()->id,
                'ref_id' => $tran_id,
                'tran_user_id' => $formData['state']['supplier_id'],
                'ref_memo' => $purchase->memo_no,

            ]);

            if ($formData['pay_amt']) {

                DB::table('voucher')->insert([
                    'date' => $formData['state']['date'],
                    'voucher_type' => 'CR',
                    'description' => @$formData['paymentState']['description'] ?? '',
                    'pay_mode' => @$formData['paymentState']['pay_mode'],
                    'tran_no' => @$formData['paymentState']['tran_no'] ?? '',
                    'amount' => $formData['pay_amt'],
                    'created_by' => Auth::user()->id,
                    'tran_type' => 'pr',
                    'ref_id' => $tran_id,
                    'tran_user_id' => $formData['state']['supplier_id'],
                    'ref_memo' => $purchase->memo_no,
                    'cash_type' => 'OUT',
                ]);
            }
            DB::commit();

            session()->flash('status', 'New purchase created successfully');
            return $this->redirect(route('purchase'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-create');
    }
}