<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hacer que purchase_id sea nullable usando SQL directo
        DB::statement('ALTER TABLE invoice_purchase_items MODIFY purchase_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Antes de hacer purchase_id NOT NULL, asegurarse de que no haya valores nulos
        // Asignar un valor por defecto temporal o mapear a compras existentes
        DB::statement("
            UPDATE invoice_purchase_items 
            SET purchase_id = (
                SELECT id FROM invoice_purchases 
                WHERE invoice_purchases.invoice_number = invoice_purchase_items.invoice_number 
                LIMIT 1
            )
            WHERE purchase_id IS NULL
        ");
        
        // Luego hacer que purchase_id sea NOT NULL
        DB::statement('ALTER TABLE invoice_purchase_items MODIFY purchase_id BIGINT UNSIGNED NOT NULL');
    }
};
