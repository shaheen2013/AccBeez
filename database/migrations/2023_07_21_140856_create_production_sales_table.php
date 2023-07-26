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
        Schema::create('production_sales', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('date')->nullable();
            $table->double('invoice_total')->default(0);
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->integer('company_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_sales');
    }
};