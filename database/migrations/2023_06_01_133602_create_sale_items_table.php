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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->string('sku');
            $table->string('name')->nullable();
            $table->double('rate')->default(0);
            $table->string('unit')->nullable();
            $table->integer('quantity')->default(0);
            $table->double('total')->default(0);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('bom_sale_item_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
