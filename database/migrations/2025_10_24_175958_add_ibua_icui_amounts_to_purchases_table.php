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
        Schema::table('invoice_purchases', function (Blueprint $table) {
            $table->decimal('ibua_amount', 10, 2)->nullable()->after('tax_amount');
            $table->decimal('icui_amount', 10, 2)->nullable()->after('ibua_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_purchases', function (Blueprint $table) {
            $table->dropColumn(['ibua_amount', 'icui_amount']);
        });
    }
};