<?php

namespace App\Livewire\Dashboard\Product\Brand;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Brand extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-brand';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultBrand()
    {
        $brands = DB::table('brand');

        if ($this->search) {
            $brands
                ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $brands->orderBy('id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-brand')]
    public function refreshBrand()
    {
        $this->resultBrand();
    }

    #[On('create-product-brand-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new ';
    }

    #[On('product-brand-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update brand';
    }
    public function render()
    {
        return view('livewire.dashboard.product.brand.brand');
    }
}