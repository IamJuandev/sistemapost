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
        Schema::table('invoice_sale_items', function (Blueprint $table) {
            $table->decimal('ibua', 10, 2)->nullable()->after('tax_amount');
            $table->decimal('icui', 10, 2)->nullable()->after('ibua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_sale_items', function (Blueprint $table) {
            $table->dropColumn(['ibua', 'icui']);
        });
    }
};