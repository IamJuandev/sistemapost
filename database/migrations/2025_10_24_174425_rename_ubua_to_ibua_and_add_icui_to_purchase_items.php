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
        Schema::table('invoice_purchase_items', function (Blueprint $table) {
            // Rename ubua column to ibua
            $table->renameColumn('ubua', 'ibua');
            
            // Add icui column for ICA (Industry and Commerce Tax)
            $table->decimal('icui', 10, 2)->nullable()->after('ibua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_purchase_items', function (Blueprint $table) {
            // Rename ibua column back to ubua
            $table->renameColumn('ibua', 'ubua');
            
            // Drop icui column
            $table->dropColumn('icui');
        });
    }
};