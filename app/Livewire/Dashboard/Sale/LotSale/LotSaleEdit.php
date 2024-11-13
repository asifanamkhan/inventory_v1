<?php

namespace App\Livewire\Dashboard\Sale\LotSale;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class LotSaleEdit extends Component
{
    public $sale_id;
    public function mount($sale_id)
    {
        $this->sale_id = $sale_id;
        // dd($this->sale_id);
    }
    #[On('updateSale')]
    public function saveSale($formData)
    {

        DB::beginTransaction();
        try {

            $sale = DB::table('sale')
                ->where('id', $this->sale_id)
                ->first();

            $formData['state']['due'] = (float)$formData['state']['total'] - (float)$sale->paid;
            $formData['state']['paid'] = $sale->paid;

            DB::table('sale')
                ->where('id', $this->sale_id)
                ->update($formData['state']);

            $sale = DB::table('sale')
                ->where('id', $this->sale_id)
                ->first();

            DB::table('product_tran_dtl')
                ->where('type','sl')
                ->where('ref_id',$this->sale_id)
                ->delete();

            foreach ($formData['saleCart'] as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['qty'],
                    'rate' => $value['sale_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->sale_id,
                    'ref_memo' => $sale->memo_no,
                    'type' => 'sl',
                    'tran_user_id' => $formData['state']['customer_id'],
                ]);
            }

            DB::commit();

            session()->flash('status', 'Sale updated successfully');
            return $this->redirect(route('sale'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.sale.lot-sale.lot-sale-edit');
    }
}