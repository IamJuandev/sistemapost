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
        // Deshabilitar temporalmente las claves foráneas
        Schema::disableForeignKeyConstraints();

        // Renombrar las tablas a sus nuevos nombres
        Schema::rename('sales', 'invoice_sales');
        Schema::rename('sale_items', 'invoice_sale_items');
        Schema::rename('purchases', 'invoice_purchases');
        Schema::rename('purchase_items', 'invoice_purchase_items');

        // Habilitar nuevamente las claves foráneas
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Deshabilitar temporalmente las claves foráneas
        Schema::disableForeignKeyConstraints();

        // Revertir los nombres a los originales
        Schema::rename('invoice_sales', 'sales');
        Schema::rename('invoice_sale_items', 'sale_items');
        Schema::rename('invoice_purchases', 'purchases');
        Schema::rename('invoice_purchase_items', 'purchase_items');

        // Habilitar nuevamente las claves foráneas
        Schema::enableForeignKeyConstraints();
    }
};
