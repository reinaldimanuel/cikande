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
    Schema::create('feeding_history', function (Blueprint $table) {
        $table->id();
        $table->timestamp('feeding_time');
        $table->string('status')->default('Terlaksana'); // Default is "Terlaksana"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_history');
    }
};
