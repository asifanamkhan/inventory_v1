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
            CREATE VIEW vw_product_info AS
            SELECT
                p.id AS product_id,
                p.u_code,
                p.code,
                p.name,
                p.varient,
                p.images,
                p.barcode,
                p.alert_qty,
                c.name AS category_name,
                c.id AS category_id,
                b.name AS brand_name,
                b.id AS brand_id,
                br.name AS branch_name,
                br.id AS branch_id,
                u.name AS unit_name,
                u.id AS unit_id,
                p.purchase_price,
                p.sale_price,
                COALESCE(SUM(CASE
                    WHEN ps.type IN ('pr', 'slrt') THEN ps.quantity
                    WHEN ps.type IN ('sl', 'prt', 'damage') THEN -ps.quantity
                    ELSE 0
                END), 0) AS stock
            FROM
                product p
            LEFT JOIN
                product_category c ON p.category_id = c.id
            LEFT JOIN
                brand b ON p.brand_id = b.id
            LEFT JOIN
                branch br ON p.branch_id = br.id
            LEFT JOIN
                unit u ON p.unit_id = u.id
            LEFT JOIN
                product_tran_dtl ps ON p.id = ps.product_id
            GROUP BY
                p.id, p.u_code, p.code, p.name, c.name, c.id,
                b.name, b.id,  br.name, br.id, u.name, u.id,
                p.purchase_price, p.sale_price, p.varient, p.barcode,
                p.images, p.alert_qty
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_product_info");
    }
};