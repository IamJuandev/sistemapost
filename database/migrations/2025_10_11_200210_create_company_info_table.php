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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('trade_name');
            $table->string('nit');
            $table->string('dv');
            $table->string('address');
            $table->string('city');
            $table->string('department');
            $table->string('country');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('email');
            $table->string('economic_activity_code');
            $table->string('regime_type');
            $table->json('tax_responsibilities');
            $table->string('software_id')->nullable();
            $table->string('software_pin')->nullable();
            $table->string('test_set_id')->nullable();
            $table->enum('environment', ['PRODUCCION', 'PRUEBAS'])->default('PRUEBAS');
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
