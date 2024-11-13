<?php

namespace App\Livewire\Dashboard\Sale\LotSale;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class LotSaleCreate extends Component
{
    #[On('saveSale')]
    public function saveSale($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {
            $formData['state']['lot_ref_memo'] = $formData['purchase_memo_no'];
            $tran_id = DB::table('sale')
                ->insertGetId($formData['state']);


            $sale = DB::table('sale')
                ->where('id', $tran_id)
                ->first();


            foreach ($formData['saleCart'] as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['qty'],
                    'rate' => $value['sale_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $tran_id,
                    'ref_memo' => $sale->memo_no,
                    'type' => 'sl',
                    'lot_ref_memo' => $formData['purchase_memo_no'],
                    'tran_user_id' => $formData['state']['customer_id'],
                ]);
            }


            DB::table('voucher')->insert([
                'date' => $formData['state']['date'],
                'voucher_type' => 'CR',
                'tran_type' => 'sl',
                'description' => 'Initial voucher for '.$sale->memo_no,
                'amount' => $formData['state']['total'],
                'created_by' => Auth::user()->id,
                'ref_id' => $tran_id,
                'tran_user_id' => $formData['state']['customer_id'],
                'ref_memo' => $sale->memo_no,

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
                    'tran_type' => 'sl',
                    'ref_id' => $tran_id,
                    'tran_user_id' => $formData['state']['customer_id'],
                    'ref_memo' => $sale->memo_no,
                    'cash_type' => 'OUT',
                ]);
            }
            DB::commit();

            session()->flash('status', 'New sale created successfully');
            return $this->redirect(route('sale'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.sale.lot-sale.lot-sale-create');
    }
}