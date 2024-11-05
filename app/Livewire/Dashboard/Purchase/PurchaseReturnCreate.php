<?php

namespace App\Livewire\Dashboard\Purchase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class PurchaseReturnCreate extends Component
{
    #[On('savePurchaseReturn')]
    public function savePurchase($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {
            $formData['state']['ref_memo_no'] =  $formData['ref_memo_no'];

            $tran_id = DB::table('purchase_return')
                ->insertGetId($formData['state']);

            $purchase = DB::table('purchase_return')
                ->where('id', $tran_id)
                ->first();

            foreach ($formData['purchaseCart'] as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['return_qty'],
                    'rate' => $value['purchase_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $tran_id,
                    'ref_memo' => $formData['state']['ref_memo_no'],
                    'return_ref_memo' => $purchase->memo_no,
                    'type' => 'prt',
                    'tran_user_id' => $formData['supplier_id'],
                ]);
            }


            DB::table('voucher')->insert([
                'date' => $formData['state']['date'],
                'voucher_type' => 'CR',
                'tran_type' => 'prt',
                'description' => 'Initial voucher for '.$purchase->memo_no,
                'amount' => $formData['state']['total'],
                'created_by' => Auth::user()->id,
                'ref_id' => $tran_id,
                'tran_user_id' => $formData['supplier_id'],
                'ref_memo' => $formData['state']['ref_memo_no'],
                'return_ref_memo' => $purchase->memo_no,

            ]);

            if ($formData['pay_amt']) {

                DB::table('voucher')->insert([
                    'date' => $formData['state']['date'],
                    'voucher_type' => 'DR',
                    'description' => @$formData['paymentState']['description'] ?? '',
                    'pay_mode' => @$formData['paymentState']['pay_mode'],
                    'tran_no' => @$formData['paymentState']['tran_no'] ?? '',
                    'amount' => $formData['pay_amt'],
                    'created_by' => Auth::user()->id,
                    'tran_type' => 'prt',
                    'ref_id' => $tran_id,
                    'tran_user_id' => $formData['supplier_id'],
                    'ref_memo' => $formData['state']['ref_memo_no'],
                    'return_ref_memo' => $purchase->memo_no,
                    'cash_type' => 'IN',
                ]);
            }
            DB::commit();

            session()->flash('status', 'Purchase returnd successfully');
            return $this->redirect(route('purchase-return'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-return-create');
    }
}