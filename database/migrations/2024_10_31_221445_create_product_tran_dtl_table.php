<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_tran_dtl', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->index();
            $table->string('ref_id')->nullable()->index();
            $table->integer('product_id')->index();
            $table->integer('tran_user_id')->nullable()->index();
            $table->string('type')->index();
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('total')->default(0);
            $table->string('ref_memo')->index();
            $table->string('return_ref_memo')->nullable();
            $table->string('lot_ref_memo')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tran_dtl');
    }
};