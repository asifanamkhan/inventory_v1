<?php

namespace App\Livewire\Dashboard\People\Supplier;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Supplier extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;



    #[Computed]
    #[On('supplier-all')]
    public function resultUser()
    {
        $suppliers = DB::table('suppliers as p');

        $suppliers
            ->orderBy('p.id', 'DESC')
            ->select(['p.*',]);

        if ($this->search) {
            $suppliers
                ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.email', 'like', '%' . $this->search . '%')
                ->orWhere('p.phone', 'like', '%' . $this->search . '%')
                ->orWhere('p.user_id', 'like', '%' . $this->search . '%')
                ->orWhere('p.address', 'like', '%' . $this->search . '%');
        }
        // $p =   $suppliers->get();
        // dd($p);

        return $suppliers->paginate($this->pagination);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function mount()
    {
        // $amt = DB::table('suppliers as p')
        //     ->select(
        //         DB::raw('SUM(supplier_amount) AS supplier_amount'),
        //     )
        //     ->count();


        // $this->supplierGrantAmt = $amt;
    }
    public function render()
    {
        return view('livewire.dashboard.people.supplier.supplier');
    }
}