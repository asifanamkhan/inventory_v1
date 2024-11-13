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
            $table->string('branch_id')->index();
            $table->string('name')->index();
            $table->string('code')->unique()->index();
            $table->string('u_code')->index();
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->integer('unit_id')->index();
            $table->integer('category_id')->index();
            $table->integer('brand_id')->index();
            $table->integer('status')->default(0)->index();
            $table->integer('alert_qty')->default(5)->index();
            $table->integer('open_stock')->default(0)->index();
            $table->string('images')->default(0);
            $table->integer('variant_type')->default(0);
            $table->text('variant_description');
            $table->integer('purchase_price');
            $table->integer('sale_price');
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
                    WHERE code LIKE CONCAT("PRO-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.code = CONCAT("PRO-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
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