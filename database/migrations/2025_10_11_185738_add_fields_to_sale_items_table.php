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
        Schema::table('sale_items', function (Blueprint $table) {
            $table->integer('line_id')->nullable();
            $table->string('description')->nullable();
            $table->string('product_code')->nullable();
            $table->string('unit_code')->nullable();
            $table->decimal('line_extension_amount', 10, 2)->nullable();
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('total_line_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn([
                'line_id',
                'description',
                'product_code',
                'unit_code',
                'line_extension_amount',
                'tax_percent',
                'tax_amount',
                'total_line_amount'
            ]);
        });
    }
};
