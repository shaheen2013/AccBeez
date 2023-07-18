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
        Schema::create('bom_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_sale_id');
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->double('rate')->default(0);
            $table->string('unit')->nullable();
            $table->float('quantity')->default(0.0000);
            $table->double('total')->default(0);
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
        Schema::dropIfExists('bom_sale_items');
    }
};
