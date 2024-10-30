<?php

namespace App\Livewire\Dashboard\People\Customer;

use Livewire\Component;

class CustomerEdit extends Component
{
    public $customer_id;
    public function mount($id){
        $this->customer_id = $id;
    }
    public function render()
    {
        return view('livewire.dashboard.people.customer.customer-edit');
    }
}