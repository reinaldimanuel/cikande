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
        $table->unsignedBigInteger('id_pond');
        $table->integer('jam_feeding');
        $table->integer('menit_feeding');
        $table->float('total_food', 8, 2);
        $table->string('status');
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
