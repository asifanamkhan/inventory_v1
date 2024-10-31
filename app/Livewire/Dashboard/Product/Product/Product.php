<?php

namespace App\Livewire\Dashboard\Product\Product;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Product extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultProduct()
    {
        $products = DB::table('vw_product_info as p');

        $products
            ->distinct('u_code')
            ->orderBy('u_code', 'DESC')
            ->select(['p.*']);

        if ($this->search) {
            $products
                ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(p.code)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(b.brand_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(c.catagory_name)'), 'like', '%' . strtolower($this->search) . '%');
        }


        return $products->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPagination()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.product.product.product');
    }
}