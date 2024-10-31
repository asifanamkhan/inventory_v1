<?php

namespace App\Livewire\Dashboard\Product\Category;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Category extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-category';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultCategory()
    {
        $categories = DB::table('product_category');

        if ($this->search) {
            $categories
                ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $categories->orderBy('id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-category')]
    public function refreshBrand()
    {
        $this->resultCategory();
    }

    #[On('create-product-category-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new ';
    }

    #[On('product-category-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update categorie';
    }
    public function render()
    {
        return view('livewire.dashboard.product.category.category');
    }
}