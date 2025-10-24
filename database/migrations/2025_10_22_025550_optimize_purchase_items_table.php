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
        // Verificar y crear campos necesarios en la tabla invoice_purchase_items si no existen
        if (!Schema::hasColumn('invoice_purchase_items', 'discount')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->decimal('discount', 10, 2)->nullable()->after('line_extension_amount');
            });
        }
        
        if (!Schema::hasColumn('invoice_purchase_items', 'total_value')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->decimal('total_value', 10, 2)->nullable()->after('tax_amount');
            });
        }
        
        // Renombrar el campo 'subtotal' en invoice_purchase_items si existe a 'item_subtotal' para evitar confusión con el subtotal general
        if (Schema::hasColumn('invoice_purchase_items', 'subtotal')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->renameColumn('subtotal', 'item_subtotal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios en caso de rollback
        if (Schema::hasColumn('invoice_purchase_items', 'item_subtotal')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->renameColumn('item_subtotal', 'subtotal');
            });
        }
        
        if (Schema::hasColumn('invoice_purchase_items', 'total_value')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->dropColumn('total_value');
            });
        }
        
        if (Schema::hasColumn('invoice_purchase_items', 'discount')) {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->dropColumn('discount');
            });
        }
    }
};
