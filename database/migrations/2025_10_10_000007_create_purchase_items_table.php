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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->integer('quantity_for_unit')->nullable();
            $table->decimal('ubua', 10, 2)->nullable();
            $table->string('unit_md')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
