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
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'invoice_prefix')) {
                $table->string('invoice_prefix')->nullable();
            }
            if (!Schema::hasColumn('sales', 'invoice_number')) {
                $table->string('invoice_number')->nullable();
            }
            if (!Schema::hasColumn('sales', 'cufe')) {
                $table->string('cufe')->nullable();
            }
            if (!Schema::hasColumn('sales', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sales', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sales', 'currency')) {
                $table->string('currency')->nullable();
            }
            if (!Schema::hasColumn('sales', 'qr_url')) {
                $table->string('qr_url')->nullable();
            }
            if (!Schema::hasColumn('sales', 'sale_type')) {
                $table->string('sale_type')->nullable(); // CONTADO o CREDITO
            }
            if (!Schema::hasColumn('sales', 'uuid')) {
                $table->string('uuid')->nullable();
            }
            if (!Schema::hasColumn('sales', 'due_date')) {
                $table->date('due_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_prefix',
                'invoice_number',
                'cufe',
                'subtotal',
                'tax_amount',
                'discount_amount',
                'currency',
                'qr_url',
                'sale_type',
                'uuid',
                'due_date'
            ]);
        });
    }
};
