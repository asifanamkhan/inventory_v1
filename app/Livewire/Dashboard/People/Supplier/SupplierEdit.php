<?php

namespace App\Livewire\Dashboard\People\Supplier;

use Livewire\Component;

class SupplierEdit extends Component
{
    public $supplier_id;
    public function mount($id){
        $this->supplier_id = $id;
    }
    public function render()
    {

        return view('livewire.dashboard.people.supplier.supplier-edit');
    }
}