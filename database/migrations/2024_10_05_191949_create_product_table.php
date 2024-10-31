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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('name');
            $table->string('code')->unique()->index();
            $table->string('u_code');
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->integer('unit_id');
            $table->integer('category_id');
            $table->integer('brand_id');
            $table->integer('status')->default(0);
            $table->integer('alert_qty')->default(5);
            $table->text('images')->default(0);
            $table->text('varient')->default(0);
            $table->integer('purchase_price')->default(0);
            $table->integer('sale_price')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();

        });

        DB::unprepared('
            CREATE TRIGGER product_code
            BEFORE INSERT ON product
            FOR EACH ROW
            BEGIN
                DECLARE current_year CHAR(2);
                DECLARE next_number INT;
                SET current_year = DATE_FORMAT(NOW(), "%y");

                SET next_number = (
                    SELECT COALESCE(MAX(CAST(SUBSTRING(code, 5, 5) AS UNSIGNED)) + 1, 1)
                    FROM product
                    WHERE code LIKE CONCAT("pro-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.code = CONCAT("pro-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS product_code');
        Schema::dropIfExists('product');
    }
};