<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class Voucher
{
    public static function purchaseStatus($prams)
    {
        $totals = DB::table('ACC_VOUCHER_INFO')
            ->select(
                DB::raw("SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as debit_total"),
                DB::raw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as credit_total")
            )
            ->first();

        $debitSum = $totals->debit_total;
        $creditSum = $totals->credit_total;

        return [
            'debitSum' => $debitSum,
            'creditSum' => $creditSum,
        ];
    }
}