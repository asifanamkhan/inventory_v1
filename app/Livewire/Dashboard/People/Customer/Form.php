<?php

namespace App\Livewire\Dashboard\People\Customer;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    public $photos = [];
    public $editPhotos = [];
    public $state = [];
    public $customer_id;

    public function mount($customer_id = null)
    {
        $this->state = (array)DB::table('customers')
            ->where('id', $customer_id)
            ->first();

        if (@$this->state['photo']) {
            $this->editPhotos = json_decode($this->state['photo']);
        }
    }


    public function save()
    {

        Validator::make($this->state, [
            'name' => 'required',

        ])->validate();

        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $file_name = time() . '-' . mt_rand() . '.' . $photo['extension'];
                Storage::putFileAs('upload/customer', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['photo'] = json_encode($allFile);
        }

        if ($this->customer_id) {
            DB::table('customers')
                ->where('id', $this->state['id'])
                ->update($this->state);

            session()->flash('status', 'Customer updated successfully.');
        } else {
            DB::table('customers')
                ->insert($this->state);

            session()->flash('status', 'Company added successfully.');
        }


        $this->reset();
        return $this->redirect(route('customer'), navigate: true);
    }
    public function render()
    {
        return view('livewire.dashboard.people.customer.form');
    }
}