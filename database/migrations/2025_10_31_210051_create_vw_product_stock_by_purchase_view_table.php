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
             CREATE VIEW vw_product_stock_by_purchase AS
            SELECT
                p.memo_no,
                pt.product_id,
                prod.name,
                prod.variant_description,
                prod.barcode,
                prod.sale_price,

                (SUM(CASE WHEN pt.type = 'pr' THEN pt.quantity ELSE 0 END) -- Total purchase quantity
                - SUM(CASE WHEN pt.type = 'prt' THEN pt.quantity ELSE 0 END) -- Subtract purchase return quantity
                - SUM(CASE WHEN pt.type = 'sl' AND pt.lot_ref_memo = p.memo_no THEN pt.quantity ELSE 0 END)  -- Subtract sale quantity using lot_ref_memo
                + SUM(CASE WHEN pt.type = 'slrt' AND pt.lot_ref_memo = p.memo_no THEN pt.quantity ELSE 0 END) -- Add sale return quantity using lot_ref_memo
                ) AS current_stock,
                SUM(CASE WHEN pt.type = 'prt' THEN pt.quantity ELSE 0 END) AS pr_return_qty,
                SUM(CASE WHEN pt.type = 'slrt' AND pt.lot_ref_memo = p.memo_no THEN pt.quantity ELSE 0 END) AS sl_return_qty,
                SUM(CASE WHEN pt.type = 'sl' AND pt.lot_ref_memo = p.memo_no THEN pt.quantity ELSE 0 END) AS sale_qty,
                (SELECT pt2.quantity
                    FROM product_tran_dtl pt2
                    WHERE pt2.product_id = pt.product_id AND pt2.type = 'pr' AND pt2.ref_memo = p.memo_no
                    ORDER BY pt2.ref_id ASC
                    LIMIT 1
                    ) AS purchase_qty
            FROM
                purchase p
            LEFT JOIN product_tran_dtl pt
                ON (pt.ref_memo = p.memo_no AND pt.type IN ('pr', 'prt'))
                OR (pt.lot_ref_memo = p.memo_no AND pt.type IN ('sl', 'slrt'))
            JOIN
                product prod ON pt.product_id = prod.id
            GROUP BY
                p.memo_no,
                prod.name,
                prod.variant_description,
                prod.barcode,
                prod.sale_price,
                pt.product_id;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_product_stock_by_purchase");
    }
};