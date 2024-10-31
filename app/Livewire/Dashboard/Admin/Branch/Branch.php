<?php

namespace App\Livewire\Dashboard\Admin\Branch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Branch extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-branch';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultBranch()
    {
        $branchs = DB::table('branch');

        if ($this->search) {
            $branchs
                ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
                ->where(DB::raw('lower(email)'), 'like', '%' . strtolower($this->search) . '%')
                ->where(DB::raw('lower(phone)'), 'like', '%' . strtolower($this->search) . '%')
                ->where(DB::raw('lower(address)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $branchs->orderBy('id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-branch')]
    public function refreshBranch()
    {
        $this->resultBranch();
    }

    #[On('create-product-branch-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new ';
    }

    #[On('product-branch-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update branch';
    }
    public function render()
    {
        return view('livewire.dashboard.admin.branch.branch');
    }
}