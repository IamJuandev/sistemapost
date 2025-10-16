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
        // Asegurar que todos los registros tengan un invoice_number único en invoice_purchases
        $purchasesWithoutInvoiceNumber = DB::table('invoice_purchases')
            ->whereNull('invoice_number')
            ->orWhere('invoice_number', '')
            ->count();
            
        if ($purchasesWithoutInvoiceNumber > 0) {
            $purchases = DB::table('invoice_purchases')
                ->whereNull('invoice_number')
                ->orWhere('invoice_number', '')
                ->get();
                
            foreach ($purchases as $purchase) {
                $newInvoiceNumber = 'TEMP-' . $purchase->id . '-' . time();
                DB::table('invoice_purchases')
                    ->where('id', $purchase->id)
                    ->update(['invoice_number' => $newInvoiceNumber]);
            }
        }
        
        // Crear índice único en invoice_number en invoice_purchases
        Schema::table('invoice_purchases', function (Blueprint $table) {
            $table->unique('invoice_number', 'invoice_purchases_invoice_number_unique');
        });
        
        // Deshabilitar temporalmente las claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Actualizar los registros de invoice_purchase_items para asegurar que todos tienen
        // el invoice_number correcto basado en su purchase_id
        $itemsWithoutInvoiceNumber = DB::table('invoice_purchase_items')
            ->whereNull('invoice_number')
            ->orWhere('invoice_number', '')
            ->count();
            
        if ($itemsWithoutInvoiceNumber > 0) {
            // Actualizar los registros que no tienen invoice_number
            DB::statement("
                UPDATE invoice_purchase_items 
                SET invoice_number = (
                    SELECT invoice_number 
                    FROM invoice_purchases 
                    WHERE invoice_purchases.id = invoice_purchase_items.purchase_id
                )
                WHERE (invoice_number IS NULL OR invoice_number = '')
                AND EXISTS (
                    SELECT 1 FROM invoice_purchases 
                    WHERE invoice_purchases.id = invoice_purchase_items.purchase_id
                )
            ");
        }
        
        // Eliminar la clave foránea antigua
        try {
            Schema::table('invoice_purchase_items', function (Blueprint $table) {
                $table->dropForeign(['purchase_id']);
            });
        } catch (\Exception $e) {
            // Si la clave foránea no existe, continuar
        }

        // Crear la nueva clave foránea basada en invoice_number
        Schema::table('invoice_purchase_items', function (Blueprint $table) {
            // Asegurarse de que invoice_number no sea nulo
            $table->string('invoice_number')->nullable(false)->change();
            
            // Crear la nueva clave foránea usando invoice_number
            $table->foreign('invoice_number', 'fk_invoice_purchase_items_invoice_number')
                  ->references('invoice_number')
                  ->on('invoice_purchases')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // Habilitar nuevamente las claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Deshabilitar temporalmente las claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Eliminar la clave foránea basada en invoice_number
        Schema::table('invoice_purchase_items', function (Blueprint $table) {
            $table->dropForeign('fk_invoice_purchase_items_invoice_number');
        });

        // Recrear la clave foránea original a purchase_id
        Schema::table('invoice_purchase_items', function (Blueprint $table) {
            $table->foreign('purchase_id', 'purchase_items_purchase_id_foreign')
                  ->references('id')
                  ->on('invoice_purchases')
                  ->onDelete('cascade');
        });

        // Eliminar el índice único de invoice_number en invoice_purchases
        Schema::table('invoice_purchases', function (Blueprint $table) {
            $table->dropUnique('invoice_purchases_invoice_number_unique');
        });

        // Habilitar nuevamente las claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
