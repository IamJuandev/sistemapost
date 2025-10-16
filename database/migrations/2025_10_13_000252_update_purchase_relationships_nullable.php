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
        // Ya verificamos que ambas columnas existen y permiten nulos:
        // - purchases.supplier_nit permite nulos
        // - purchase_items.invoice_number permite nulos
        
        // Si se necesita hacer alguna modificación específica a las relaciones,
        // se puede hacer aquí. Pero actualmente ya permiten valores nulos.
        
        // Solo como ejemplo de cómo se haría si se tuviera que modificar:
        /* 
        Schema::table('purchases', function (Blueprint $table) {
            // Cambiar la columna supplier_nit para asegurar que permite nulos
            $table->string('supplier_nit')->nullable()->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            // Cambiar la columna invoice_number para asegurar que permite nulos
            $table->string('invoice_number')->nullable()->change();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir solo si se hicieron cambios específicos
    }
};
