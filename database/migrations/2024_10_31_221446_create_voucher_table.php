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
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->index();
            $table->string('date')->index();
            $table->integer('pay_mode')->index();
            $table->string('voucher_no')->index();
            $table->string('tran_no')->nullable();
            $table->text('description')->nullable();
            $table->string('voucher_type')->index();
            $table->string('tran_type')->index();
            $table->string('cash_type')->index()->nullable();
            $table->integer('first_pay')->default(0);
            $table->double('amount');
            $table->string('ref_id')->nullable()->index();
            $table->integer('tran_user_id')->nullable()->index();
            $table->string('ref_memo')->index();
            $table->string('return_ref_memo')->nullable()->index();
            $table->string('lot_ref_memo')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER voucher_voucher_no
            BEFORE INSERT ON voucher
            FOR EACH ROW
            BEGIN
                DECLARE current_year CHAR(2);
                DECLARE next_number INT;
                SET current_year = DATE_FORMAT(NOW(), "%y");

                SET next_number = (
                    SELECT COALESCE(MAX(CAST(SUBSTRING(voucher_no, 5, 5) AS UNSIGNED)) + 1, 1)
                    FROM voucher
                    WHERE voucher_no LIKE CONCAT("VR-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.voucher_no = CONCAT("VR-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS voucher_voucher_no');
        Schema::dropIfExists('voucher');
    }
};