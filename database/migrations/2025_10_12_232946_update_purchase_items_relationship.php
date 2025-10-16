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
        // Agregar la columna invoice_number a la tabla purchase_items
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('purchase_id');
        });
        
        // Actualizar registros existentes con el invoice_number correspondiente
        DB::statement("
            UPDATE purchase_items 
            SET invoice_number = (
                SELECT invoice_number 
                FROM purchases 
                WHERE purchases.id = purchase_items.purchase_id
            )
            WHERE EXISTS (
                SELECT 1 
                FROM purchases 
                WHERE purchases.id = purchase_items.purchase_id
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
        });
    }
};
