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
        Schema::table('purchase_items', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_items', 'line_id')) {
                $table->integer('line_id')->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'description')) {
                $table->string('description')->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'product_code')) {
                $table->string('product_code')->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'unit_code')) {
                $table->string('unit_code')->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'line_extension_amount')) {
                $table->decimal('line_extension_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'tax_percent')) {
                $table->decimal('tax_percent', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('purchase_items', 'total_line_amount')) {
                $table->decimal('total_line_amount', 10, 2)->nullable();
            }
            if (Schema::hasColumn('purchase_items', 'cost_price')) {
                $table->renameColumn('cost_price', 'unit_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
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
            $table->renameColumn('unit_price', 'cost_price');
        });
    }
};
