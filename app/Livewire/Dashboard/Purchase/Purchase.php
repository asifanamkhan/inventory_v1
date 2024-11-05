<?php

namespace App\Livewire\Dashboard\Purchase;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Purchase extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $rt_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $purchaseGrantAmt = 0;
    public $purchasePaidAmt = 0;
    public $purchaseRtAmt = 0;
    public $purchaseDueAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;

    public $searchMemo, $searchSupplier, $searchStatus,
        $searchPayStatus, $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('purchase-all')]
    public function resultPurchase()
    {
        $purchases = DB::table('vw_purchase as p');

        $purchases
            ->orderBy('p.purchase_id', 'DESC')
            ->select(['p.*']);

        if ($this->search) {
            $purchases
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.total', 'like', '%' . $this->search . '%')
                ->orWhere('p.paid', 'like', '%' . $this->search . '%');
        }
        if ($this->searchMemo) {
            $purchases->where('p.memo_no', 'like', '%' . $this->searchMemo . '%');
        }
        if ($this->searchSupplier) {
            $purchases->where('p.supplier_name', 'like', '%' . $this->searchMemo . '%');
        }

        if ($this->searchStatus) {
            $purchases->where('p.status', $this->searchStatus);
        }
        if ($this->searchPayStatus) {
            $purchases->where('p.payment_status', $this->searchPayStatus);
        }

        if ($this->firstFilterDate) {
            $purchases->where('p.date', '>=', $this->firstFilterDate);
        }

        if ($this->lastFilterDate) {
            $purchases->where('p.date', '<=', $this->lastFilterDate);
        }


        // $p =   $purchases->get();
        // dd($p);

        return $purchases->paginate($this->pagination);
    }

    public function dateFilter()
    {
        $dates = explode('-', $this->searchDate);
        $this->firstFilterDate = Carbon::parse($dates[0])->format('Y-m-d');
        $this->lastFilterDate = Carbon::parse($dates[1])->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectPageRows()
    {
        if ($this->selectPageRows) {
            $this->selectRows = $this->resultPurchase->pluck('id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }

    #[On('purchase-all')]
    public function grandCalc()
    {
        $amt = DB::table('vw_purchase as p')
            ->select(
                DB::raw('SUM(total) AS total'),
                DB::raw('SUM(paid) AS paid'),
                DB::raw('SUM(pr_return) AS tot_return'),
                DB::raw('SUM(due) AS due'),
            )
            ->first();

        $this->purchaseRtAmt = $amt->tot_return;
        $this->purchaseGrantAmt = $amt->total;
        $this->purchasePaidAmt = $amt->paid;
        $this->purchaseDueAmt = $amt->due;
    }

    public function mount()
    {
        $this->grandCalc();
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase');
    }
}