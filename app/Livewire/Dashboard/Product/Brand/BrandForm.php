<?php

namespace App\Livewire\Dashboard\Product\Brand;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrandForm extends Component
{
    public $state = [];
    public $editForm = false;


    public function store()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:brand',

        ])->validate();

        DB::table('brand')->insert([
            'name' => $this->state['name'],
            'created_by' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-brand');
        session()->flash('status', 'Product brand create successfully');

        $this->reset();
    }

    #[On('create-product-brand-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-brand-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->state = (array)DB::table('brand')
                    ->where('id', $id)
                    ->first();
    }

    public function update()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:brand,name,' . $this->state['id'],
        ])->validate();


        DB::table('brand')
            ->where('id', $this->state['id'])
            ->update([
                'name' => $this->state['name'],
                'updated_by' => Auth::user()->id
            ]);

        $this->dispatch('refresh-product-brand');
        session()->flash('status', 'Product brand updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.brand.brand-form');
    }
}