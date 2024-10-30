<?php

namespace App\Livewire\Dashboard\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class Company extends Component
{
    public $update = false;
    public $isTure = false;
    public $company;
    public $photos = [];
    public $editPhotos = [];
    public $state = [];


    public function mount()
    {
        $this->state = (array)DB::table('company')
            ->first();

        if (@$this->state['logo']) {
            $this->editPhotos = json_decode($this->state['logo']);
        }
    }


    public function save()
    {

        Validator::make($this->state, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ])->validate();

        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $file_name = time() . '-' . mt_rand() . '.' . $photo['extension'];
                Storage::putFileAs('upload/company', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['logo'] = json_encode($allFile);
        }

        // $this->editPhotos = json_decode($this->state['logo']);
        // $this->photos = [];

        DB::table('company')
            ->where('id', $this->state['id'])
            ->update($this->state);

        session()->flash('status', 'Company information updated successfully.');
    }
    public function render()
    {
        return view('livewire.dashboard.admin.company');
    }
}
