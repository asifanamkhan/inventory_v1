<?php

namespace App\Http\Controllers;

use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function paymentReport(Request $request)
    {

        $query = DB::table('VW_ACC_VOUCHER_INFO')
            ->where('cash_type', '!=', null);

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }
        if ($request->tran_type) {
            $query->where('tran_type', $request->tran_type);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->cash_type) {
            $query->where('cash_type', $request->cash_type);
        }
        if ($request->pay_mode) {
            $query->where('pay_mode', $request->pay_mode);
        }

        $payments = $query->orderBy('voucher_id', 'DESC')->get();

        $data = [
            'ledgers' => $payments,
            'state' => [
                'cash_type' => $request->cash_type,
                'tran_type' => $request->tran_type,
            ]
        ];

        $html = view()->make('livewire.dashboard.account-reports.pdf.payments-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'payments-reports.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function transactionReport(Request $request)
    {
        $query = DB::table('VW_ACC_VOUCHER_INFO')
            ->where('cash_type', '!=', null);

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }

        if ($request->pay_mode) {
            $query->where('pay_mode', $request->pay_mode);
        }

        if ($request->cash_type) {
            $query->where('cash_type', $request->cash_type);
        }
        $payments = $query->orderBy('voucher_id', 'DESC')->get();

        $data = [
            'ledgers' => $payments,
            'state' => [
                'cash_type' => $request->cash_type,
                'tran_type' => $request->tran_type,
            ]
        ];

        $html = view()->make('livewire.dashboard.account-reports.pdf.transaction-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'payments-reports.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function trialBalance(Request $request)
    {
        $query = DB::table('VW_ACC_VOUCHER_INFO');

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }
        if ($request->tran_type) {
            $query->where('tran_type', $request->tran_type);
        }
        if ($request->pay_mode) {
            $query->where('pay_mode', $request->pay_mode);
        }

        $payments = $query->orderBy('voucher_id', 'ASC')->get();

        $data = [
            'ledgers' => $payments,
        ];

        $html = view()->make('livewire.dashboard.account-reports.pdf.trial-balance-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'payments-reports.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }
}