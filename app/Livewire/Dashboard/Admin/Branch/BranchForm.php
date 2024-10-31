<?php

namespace App\Livewire\Dashboard\Admin\Branch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchForm extends Component
{
    public $state = [];
    public $editForm = false;


    public function store()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:branch',
            'phone' => 'required',
            'address' => 'required',

        ])->validate();

        DB::table('branch')->insert([
            'name' => $this->state['name'],
            'phone' => $this->state['phone'],
            'email' => $this->state['email'],
            'address' => $this->state['address'],
            // 'description' => $this->state['description'],
            'created_by' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-branch');
        session()->flash('status', 'Product branch create successfully');

        $this->reset();
    }

    #[On('create-product-branch-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-branch-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->state = (array)DB::table('branch')
                    ->where('id', $id)
                    ->first();
    }

    public function update()
    {
        Validator::make($this->state, [
            'name' => 'required|unique:branch,name,' . $this->state['id'],
            'phone' => 'required',
            'address' => 'required',
        ])->validate();


        DB::table('branch')
            ->where('id', $this->state['id'])
            ->update([
                'name' => $this->state['name'],
                'phone' => $this->state['phone'],
                'email' => $this->state['email'],
                'address' => $this->state['address'],
                // 'description' => $this->state['description'],
                'updated_by' => Auth::user()->id
            ]);

        $this->dispatch('refresh-product-branch');
        session()->flash('status', 'Product branch updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.admin.branch.branch-form');
    }
}