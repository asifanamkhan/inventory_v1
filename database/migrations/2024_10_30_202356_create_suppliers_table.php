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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user_id')->index();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->integer('branch_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER suppliers_user_id
            BEFORE INSERT ON suppliers
            FOR EACH ROW
            BEGIN
                DECLARE current_year CHAR(2);
                DECLARE next_number INT;
                SET current_year = DATE_FORMAT(NOW(), "%y");

                SET next_number = (
                    SELECT COALESCE(MAX(CAST(SUBSTRING(user_id, 5, 5) AS UNSIGNED)) + 1, 1)
                    FROM suppliers
                    WHERE user_id LIKE CONCAT("SUP-%", "/", current_year) COLLATE utf8mb4_unicode_ci
                );

                SET NEW.user_id = CONCAT("SUP-", LPAD(next_number, 5, "0"), "/", current_year) COLLATE utf8mb4_unicode_ci;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS suppliers_user_id');
        Schema::dropIfExists('suppliers');
    }
};