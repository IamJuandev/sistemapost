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
        Schema::table('invoice_sale_items', function (Blueprint $table) {
            $table->string('description')->nullable(true)->change();
            $table->string('product_code')->nullable(true)->change();
            $table->string('unit_code')->nullable(true)->change();
            $table->integer('line_id')->nullable(true)->change();
            $table->decimal('selling_price', 10, 2)->nullable(true)->change();
            $table->decimal('line_extension_amount', 10, 2)->nullable(true)->change();
            $table->decimal('tax_percent', 5, 2)->nullable(true)->change();
            $table->decimal('tax_amount', 10, 2)->nullable(true)->change();
            $table->decimal('total_line_amount', 10, 2)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_sale_items', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
            $table->string('product_code')->nullable(false)->change();
            $table->string('unit_code')->nullable(false)->change();
            $table->integer('line_id')->nullable(false)->change();
            $table->decimal('selling_price', 10, 2)->nullable(false)->change();
            $table->decimal('line_extension_amount', 10, 2)->nullable(false)->change();
            $table->decimal('tax_percent', 5, 2)->nullable(false)->change();
            $table->decimal('tax_amount', 10, 2)->nullable(false)->change();
            $table->decimal('total_line_amount', 10, 2)->nullable(false)->change();
        });
    }
};
