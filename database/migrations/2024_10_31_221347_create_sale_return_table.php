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
        Schema::create('sale_return', function (Blueprint $table) {
            $table->id();
            $table->string('date')->index();
            $table->string('memo_no')->unique()->index();
            $table->string('ref_memo_no')->nullable()->index();
            $table->string('sale_id')->nullable()->index();
            $table->integer('customer_id')->index();
            $table->integer('branch_id')->index();
            $table->double('shipping')->nullable();
            $table->double('qty')->nullable();
            $table->double('net_total')->default(0);
            $table->double('total')->default(0);
            $table->double('paid')->default(0);
            $table->double('due')->default(0);
            $table->double('tax')->default(0);
            $table->string('payment_status');
            $table->integer('status')->default(1);
            $table->text('remarks')->nullable();
            $table->string('document')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER sale_return_memo_no
            BEFORE INSERT ON sale_return
            FOR EACH ROW
            BEGIN
                DECLARE current_year CHAR(2);
                DECLARE next_number INT;
                SET current_year = DATE_FORMAT(NOW(), "%y");

                SET next_number = (
                    SELECT COALESCE(MAX(CAST(SUBSTRING(memo_no, 5, 5) AS UNSIGNED)) + 1, 1)
                    FROM sale_return
                    WHERE memo_no LIKE CONCAT("SLRT-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.memo_no = CONCAT("SLRT-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS sale_return_memo_no');
        Schema::dropIfExists('sale_return');
    }
};
