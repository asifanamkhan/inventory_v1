<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW vw_sale AS
            SELECT
                p.memo_no,
                p.lot_ref_memo,
                p.id AS sale_id,
                p.total,
                p.date,
                p.shipping,
                p.qty,
                p.net_total,
                p.tax,
                p.discount,
                p.status,
                p.payment_status,
                s.name AS customer_name,
                s.id AS customer_id,
                b.name AS branch_name,
                b.id AS branch_id,
                COALESCE(SUM(CASE WHEN v.tran_type = 'sl' AND v.voucher_type = 'DR' THEN v.amount ELSE 0 END), 0) AS paid,
                COALESCE(SUM(CASE WHEN v.tran_type = 'slrt' AND v.voucher_type = 'DR' THEN v.amount ELSE 0 END), 0) AS sl_return,
                COALESCE(SUM(CASE WHEN v.tran_type = 'slrt' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0) AS sl_return_paid,

                 p.total - COALESCE(SUM(CASE WHEN v.tran_type = 'sl' AND v.voucher_type = 'DR' THEN v.amount ELSE 0 END), 0)
                 AS sale_due,

                (p.total - COALESCE(SUM(CASE WHEN v.tran_type = 'slrt' AND v.voucher_type = 'DR' THEN v.amount ELSE 0 END), 0))
                - COALESCE(SUM(CASE WHEN v.tran_type = 'sl' AND v.voucher_type = 'DR' THEN v.amount ELSE 0 END), 0)
                + COALESCE(SUM(CASE WHEN v.tran_type = 'slrt' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0)
                AS total_due

            FROM
                sale p
            LEFT JOIN
                voucher v ON p.memo_no = v.ref_memo
            LEFT JOIN
                customers s ON p.customer_id = s.id
            LEFT JOIN
                branch b ON p.branch_id = b.id
                GROUP BY
                p.memo_no, p.total, p.id, p.date, p.shipping, p.qty,
                p.net_total, p.tax, p.discount, p.status, p.payment_status,
                s.name, s.id, b.name, b.id, p.lot_ref_memo
            ;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_sale");
    }
};