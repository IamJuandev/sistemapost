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
        // Esta migración ya no elimina tax_rate, así que no se hace nada
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de reversión, agregaríamos de nuevo el campo
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->nullable(); // Same as original
        });
    }
};