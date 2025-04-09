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
        Schema::create('feeding_schedule', function (Blueprint $table) {
            $table->id();
            $table->time('feeding_time');
            $table->enum('status', ['Belum', 'Sedang Berlangsung', 'Terlaksana'])->default('Belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_schedule');
    }
};
