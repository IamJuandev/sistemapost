<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la columna supplier_nit ya existe
        $columns = DB::getSchemaBuilder()->getColumnListing('purchases');
        $hasSupplierNitColumn = in_array('supplier_nit', $columns);
        
        if (!$hasSupplierNitColumn) {
            // Agregar la columna supplier_nit a la tabla purchases
            Schema::table('purchases', function (Blueprint $table) {
                $table->string('supplier_nit')->nullable()->after('supplier_id');
            });
        }

        // Actualizar los registros existentes con el NIT del proveedor correspondiente
        DB::statement("
            UPDATE purchases 
            SET supplier_nit = (
                SELECT nit 
                FROM suppliers 
                WHERE suppliers.id = purchases.supplier_id
            )
            WHERE supplier_nit IS NULL
            AND EXISTS (
                SELECT 1 
                FROM suppliers 
                WHERE suppliers.id = purchases.supplier_id
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'supplier_nit')) {
                $table->dropColumn('supplier_nit');
            }
        });
    }
};
