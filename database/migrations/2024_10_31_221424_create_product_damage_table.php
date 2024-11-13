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
        Schema::create('product_damage', function (Blueprint $table) {
            $table->id();
            $table->string('date')->index();
            $table->string('memo_no')->unique()->index();
            $table->integer('branch_id')->index();
            $table->double('net_total')->default(0);
            $table->double('total')->default(0);
            $table->double('quantity')->default(0);
            $table->double('tax')->default(0);
            $table->integer('status')->default(1);
            $table->text('remarks')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER product_damage_memo_no
            BEFORE INSERT ON product_damage
            FOR EACH ROW
            BEGIN
                DECLARE current_year CHAR(2);
                DECLARE next_number INT;
                SET current_year = DATE_FORMAT(NOW(), "%y");

                SET next_number = (
                    SELECT COALESCE(MAX(CAST(SUBSTRING(memo_no, 5, 5) AS UNSIGNED)) + 1, 1)
                    FROM product_damage
                    WHERE memo_no LIKE CONCAT("DMG-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.memo_no = CONCAT("DMG-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS product_damage_memo_no');
        Schema::dropIfExists('product_damage');
    }
};