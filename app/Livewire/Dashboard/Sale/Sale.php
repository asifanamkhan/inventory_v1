<?php

namespace App\Livewire\Dashboard\Sale;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Sale extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $rt_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $saleGrantAmt = 0;
    public $salePaidAmt = 0;
    public $saleRtAmt = 0;
    public $saleDueAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;

    public $searchMemo, $searchCustomer, $searchStatus,
        $searchPayStatus, $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('sale-all')]
    public function resultSale()
    {
        $sales = DB::table('vw_sale as p');

        $sales
            ->orderBy('p.sale_id', 'DESC')
            ->select(['p.*']);

        if ($this->search) {
            $sales
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.total', 'like', '%' . $this->search . '%')
                ->orWhere('p.paid', 'like', '%' . $this->search . '%');
        }
        if ($this->searchMemo) {
            $sales->where('p.memo_no', 'like', '%' . $this->searchMemo . '%');
        }
        if ($this->searchCustomer) {
            $sales->where('p.customer_name', 'like', '%' . $this->searchMemo . '%');
        }

        if ($this->searchStatus) {
            $sales->where('p.status', $this->searchStatus);
        }
        if ($this->searchPayStatus) {
            $sales->where('p.payment_status', $this->searchPayStatus);
        }

        if ($this->firstFilterDate) {
            $sales->where('p.date', '>=', $this->firstFilterDate);
        }

        if ($this->lastFilterDate) {
            $sales->where('p.date', '<=', $this->lastFilterDate);
        }


        // $p =   $sales->get();
        // dd($p);

        return $sales->paginate($this->pagination);
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
            $this->selectRows = $this->resultSale->pluck('id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }

    #[On('sale-all')]
    public function grandCalc()
    {
        $amt = DB::table('vw_sale as p')
            ->select(
                DB::raw('SUM(total) AS total'),
                DB::raw('SUM(paid) AS paid'),
                DB::raw('SUM(sl_return) AS tot_return'),
                DB::raw('SUM(total_due) AS total_due'),
            )
            ->first();

        $this->saleRtAmt = $amt->tot_return;
        $this->saleGrantAmt = $amt->total;
        $this->salePaidAmt = $amt->paid;
        $this->saleDueAmt = $amt->total_due;
    }
    public function mount()
    {
        $this->grandCalc();
    }
    public function render()
    {
        return view('livewire.dashboard.sale.sale');
    }
}