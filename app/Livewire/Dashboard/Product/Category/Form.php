<?php

namespace App\Livewire\Dashboard\Product\Category;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Form extends Component
{
    public $state = [];
    public $editForm = false;


    public function store()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:product_category',

        ])->validate();

        DB::table('product_category')->insert([
            'name' => $this->state['name'],
            'created_by' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-category');
        session()->flash('status', 'Product category create successfully');

        $this->reset();
    }

    #[On('create-product-category-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-category-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->state = (array)DB::table('product_category')
                    ->where('id', $id)
                    ->first();
    }

    public function update()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:product_category,name,' . $this->state['id'],
        ])->validate();


        DB::table('product_category')
            ->where('id', $this->state['id'])
            ->update([
                'name' => $this->state['name'],
                'updated_by' => Auth::user()->id
            ]);

        $this->dispatch('refresh-product-category');
        session()->flash('status', 'Product category updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.category.form');
    }
}