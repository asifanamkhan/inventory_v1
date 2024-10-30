<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function supplierInfo()
    {
        $suppliers = DB::table('VW_INV_SUPPLIER_INFO')->get();
        $data = [
            'suppliers' => $suppliers
        ];

        $html = view()->make('livewire.dashboard.reports.supplier.supplier-info', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'supplier-info.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }

    public function supplierLedgerPdf($code)
    {
        $ledgers = DB::table('VW_INV_SUPPLIER_PAYMENT_LEDGER')
            ->where('p_code', $code)
            ->get();

        $data = [
            'ledgers' => $ledgers
        ];

        $html = view()->make('livewire.dashboard.reports.supplier.supplier-ledger-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'supplier-ledger.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }
}