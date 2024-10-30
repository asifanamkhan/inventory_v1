<?php

namespace App\Http\Controllers;

use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function invoice($expense_mst_id)
    {
        $mst = DB::table('ACC_EXPENSE_MST as p')
            ->where('expense_mst_id', $expense_mst_id)
            ->leftJoin('ACC_EXPENSES_LIST as s', function ($join) {
                $join->on('s.expense_id', '=', 'p.expense_type');
            })
            ->select(['p.*', 's.expense_type as p_name'])
            ->first();

        $dtl = DB::table('ACC_EXPENSE_DTLS')
            ->where('expense_mst_id', $expense_mst_id)
            ->get();


        $data = [
            'ledgers' => $dtl,
            'mst' => $mst
        ];

        $html = view()->make('livewire.dashboard.reports.expense.pdf.invoice', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-return-sale.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }
}