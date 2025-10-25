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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "IVA", "ICA", "IBUA"
            $table->string('code', 10); // Numeric code for the tax
            $table->decimal('rate', 10, 2); // Tax rate as percentage or fixed amount
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // 'percentage' for VAT, 'fixed' for fixed amounts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create pivot table for many-to-many relationship between products and taxes
        Schema::create('product_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_id')->constrained()->onDelete('cascade');
            $table->decimal('rate_override', 10, 2)->nullable(); // Allow specific rate override for this product
            $table->timestamps();
            
            // Prevent duplicate product-tax combinations
            $table->unique(['product_id', 'tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_taxes');
        Schema::dropIfExists('taxes');
    }
};