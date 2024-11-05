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
        CREATE VIEW vw_purchase AS
        SELECT
            p.memo_no,
            p.id AS purchase_id,
            p.total,
            p.date,
            p.shipping,
            p.qty,
            p.net_total,
            p.tax,
            p.discount,
            p.status,
            p.payment_status,
            s.name AS supplier_name,
            s.id AS supplier_id,
            b.name AS branch_name,
            b.id AS branch_id,
            COALESCE(SUM(CASE WHEN v.tran_type = 'pr' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0) AS paid,
            COALESCE(SUM(CASE WHEN v.tran_type = 'prt' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0) AS pr_return,
            (p.total - COALESCE(SUM(CASE WHEN v.tran_type = 'prt' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0))
            - COALESCE(SUM(CASE WHEN v.tran_type = 'pr' AND v.voucher_type = 'CR' THEN v.amount ELSE 0 END), 0) AS due

        FROM
            purchase p
        LEFT JOIN
            voucher v ON p.memo_no = v.ref_memo
        LEFT JOIN
            suppliers s ON p.supplier_id = s.id
        LEFT JOIN
            branch b ON p.branch_id = b.id
        
        GROUP BY
            p.memo_no, p.id, p.total, p.date, p.shipping, p.qty,
            p.net_total, p.tax, p.discount, p.status,
            p.payment_status, s.name, s.id, b.name, b.id;   ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_purchase");
    }
};