<?php

namespace App\Livewire\Dashboard\Product\Product;

use Livewire\Component;

class ProductEdit extends Component
{
    public $u_code;
    public function mount($u_code)
    {
        $this->u_code = $u_code;
    }
    public function render()
    {
        return view('livewire.dashboard.product.product.product-edit');
    }
}