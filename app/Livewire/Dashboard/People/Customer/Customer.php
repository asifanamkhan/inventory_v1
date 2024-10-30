<?php

namespace App\Livewire\Dashboard\People\Customer;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Customer extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;



    #[Computed]
    #[On('customer-all')]
    public function resultUser()
    {
        $customers = DB::table('customers as p');

        $customers
            ->orderBy('p.id', 'DESC')
            ->select(['p.*',]);

        if ($this->search) {
            $customers
                ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.email', 'like', '%' . $this->search . '%')
                ->orWhere('p.phone', 'like', '%' . $this->search . '%')
                ->orWhere('p.user_id', 'like', '%' . $this->search . '%')
                ->orWhere('p.address', 'like', '%' . $this->search . '%');
        }
        // $p =   $customers->get();
        // dd($p);

        return $customers->paginate($this->pagination);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function mount()
    {
        // $amt = DB::table('customers as p')
        //     ->select(
        //         DB::raw('SUM(customer_amount) AS customer_amount'),
        //     )
        //     ->count();


        // $this->customerGrantAmt = $amt;
    }
    public function render()
    {
        return view('livewire.dashboard.people.customer.customer');
    }
}