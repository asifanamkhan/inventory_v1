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
            CREATE VIEW vw_product_tran_dtl AS
                SELECT
                ptd.*,
                CASE
                    WHEN ptd.type IN ('pr', 'prt') THEN s.name
                    WHEN ptd.type IN ('sl', 'slrt') THEN c.name
                    ELSE NULL
                END AS related_party_name,
                prod.name AS product_name,
                prod.code AS product_code,
                prod.variant_description AS variant_description,
                b.name AS branch_name
            FROM
                product_tran_dtl ptd
            LEFT JOIN
                suppliers s ON ptd.type IN ('pr', 'prt') AND ptd.tran_user_id = s.id
            LEFT JOIN
                customers c ON ptd.type IN ('sl', 'slrt') AND ptd.tran_user_id = c.id
            JOIN
                product prod ON ptd.product_id = prod.id
            JOIN
                branch b ON ptd.branch_id = b.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_product_tran_dtl");
    }
};
