<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sensor_settings', function (Blueprint $table) {
        $table->id();
        $table->decimal('min_ph', 4, 2);
        $table->decimal('max_ph', 4, 2);
        $table->decimal('min_temperature', 5, 2);
        $table->decimal('max_temperature', 5, 2);
        $table->integer('min_tds');
        $table->integer('max_tds');
        $table->integer('min_conductivity');
        $table->integer('max_conductivity');
        $table->decimal('min_salinity', 5, 2);
        $table->decimal('max_salinity', 5, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_settings');
    }
};
