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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('invoice_prefix')->nullable();
            $table->string('cufe')->nullable();
            $table->string('supplier_nit')->nullable();
            $table->string('supplier_name')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('withholding_amount', 10, 2)->nullable();
            $table->decimal('total_with_tax', 10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('authorization_number')->nullable();
            $table->date('authorization_expiration')->nullable();
            $table->string('qr_url')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('uuid')->nullable();
            $table->date('due_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('xml_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_prefix',
                'cufe',
                'supplier_nit',
                'supplier_name',
                'subtotal',
                'tax_amount',
                'withholding_amount',
                'total_with_tax',
                'currency',
                'authorization_number',
                'authorization_expiration',
                'qr_url',
                'purchase_type',
                'uuid',
                'due_date',
                'xml_path'
            ]);
        });
    }
};
