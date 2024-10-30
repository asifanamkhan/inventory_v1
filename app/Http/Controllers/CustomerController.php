<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function customerInfo()
    {
        $customers = DB::table('VW_INV_CUSTOMER_INFO')->get();
        $data = [
            'customers' => $customers
        ];
        $html = view()->make('livewire.dashboard.reports.customer.customer-info', $data)->render();
        $pdf_data = [
            'html' => $html,
            'filename' => 'customer-info.pdf',
        ];
        GeneratePdf::generate($pdf_data);

    }

    public function customerLedgerPdf($code)
    {
        $ledgers = DB::table('VW_INV_CUSTOMER_PAYMENT_LEDGER')
            ->where('customer_id', $code)
            ->get();

        $data = [
            'ledgers' => $ledgers
        ];

        $html = view()->make('livewire.dashboard.reports.customer.customer-ledger-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'customer-ledger.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }
}