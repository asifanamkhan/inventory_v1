<?php

namespace App\Livewire\Dashboard\Purchase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class PurchaseEdit extends Component
{
    public $purchase_id;
    public function mount($purchase_id)
    {
        $this->purchase_id = $purchase_id;
        // dd($this->purchase_id);
    }
    #[On('updatePurchase')]
    public function savePurchase($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {

            $purchase = DB::table('purchase')
                ->where('id', $this->purchase_id)
                ->first();

            $formData['state']['due'] = (float)$formData['state']['total'] - (float)$purchase->paid;
            $formData['state']['paid'] = $purchase->paid;

            $tran_id = DB::table('purchase')
                ->where('id', $this->purchase_id)
                ->update($formData['state']);

            $purchase = DB::table('purchase')
                ->where('id', $tran_id)
                ->first();

            DB::table('product_tran_dtl')
                ->where('type','pr')
                ->where('ref_id',$this->purchase_id)
                ->delete();

            foreach ($formData['purchaseCart'] as $key => $value) {
                DB::table('product_tran_dtl')->insert([
                    'branch_id' => 1,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['qty'],
                    'rate' => $value['purchase_price'],
                    'total' => $value['line_total'],
                    'created_by' => Auth::user()->id,
                    'ref_id' => $this->purchase_id,
                    'ref_memo' => $purchase->memo_no,
                    'type' => 'pr',
                    'tran_user_id' => $formData['state']['supplier_id'],
                ]);
            }

            DB::commit();

            session()->flash('status', 'Purchase updated successfully');
            return $this->redirect(route('purchase'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase-edit');
    }
}