<?php

namespace App\Livewire\Dashboard\Product\Unit;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductUnitForm extends Component
{
    public $state = [];
    public $editForm = false;


    public function store()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:unit',

        ])->validate();

        DB::table('unit')->insert([
            'name' => $this->state['name'],
            'created_by' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-unit');
        session()->flash('status', 'Product unit create successfully');

        $this->reset();
    }

    #[On('create-product-unit-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-unit-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->state = (array)DB::table('unit')
            ->where('id', $id)
            ->first();
    }

    public function update()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:unit,name,' . $this->state['id'],
        ])->validate();


        DB::table('unit')
            ->where('id', $this->state['id'])
            ->update([
                'name' => $this->state['name'],
                'updated_by' => Auth::user()->id
            ]);

        $this->dispatch('refresh-product-unit');
        session()->flash('status', 'Product unit updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.unit.product-unit-form');
    }
}