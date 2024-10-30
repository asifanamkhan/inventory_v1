<?php

namespace App\Livewire\Dashboard\Admin\User;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class UserList extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;
    public $userGrantAmt = 0;
    public $userPaidAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;


    #[Computed]
    #[On('user-all')]
    public function resultUser()
    {
        $users = DB::table('users as p');

        $users
            ->orderBy('p.id', 'DESC')
            ->where('user_type', 1)
            ->select(['p.*',]);

        if ($this->search) {
            $users
                ->where(DB::raw('lower(p.name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.email', 'like', '%' . $this->search . '%')
                ->orWhere('p.phone', 'like', '%' . $this->search . '%');
        }
        // $p =   $users->get();
        // dd($p);

        return $users->paginate($this->pagination);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectPageRows()
    {
        if ($this->selectPageRows) {
            $this->selectRows = $this->resultPurchase->pluck('tran_mst_id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }

    public function mount()
    {
        // $amt = DB::table('users as p')
        //     ->select(
        //         DB::raw('SUM(user_amount) AS user_amount'),
        //     )
        //     ->count();


        // $this->userGrantAmt = $amt;
    }
    public function render()
    {
        return view('livewire.dashboard.admin.user.user-list');
    }
}