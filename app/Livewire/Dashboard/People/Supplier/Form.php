<?php

namespace App\Livewire\Dashboard\People\Supplier;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    public $photos = [];
    public $editPhotos = [];
    public $state = [];
    public $supplier_id;

    public function mount($supplier_id = null)
    {
        if ($supplier_id) {
            $this->state = (array)DB::table('suppliers')
                ->where('id', $supplier_id)
                ->first();

            if (@$this->state['photo']) {
                $this->editPhotos = json_decode($this->state['photo']);
            }
        }
        $this->state['branch_id'] = 1;
    }


    public function save()
    {

        Validator::make($this->state, [
            'name' => 'required',

        ])->validate();

        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $file_name = time() . '-' . mt_rand() . '.' . $photo['extension'];
                Storage::putFileAs('upload/supplier', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['photo'] = json_encode($allFile);
        }

        if ($this->supplier_id) {
            $this->state['updated_by'] = Auth::user()->id;
            DB::table('suppliers')
                ->where('id', $this->state['id'])
                ->update($this->state);
        } else {
            $this->state['created_by'] = Auth::user()->id;
            DB::table('suppliers')
                ->insert($this->state);
        }

        session()->flash('status', 'Company information updated successfully.');
        $this->reset();
        return $this->redirect(route('supplier'), navigate: true);
    }
    public function render()
    {
        return view('livewire.dashboard.people.supplier.form');
    }
}