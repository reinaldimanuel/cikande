<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('new_email')->nullable();
        $table->string('email_change_token')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['new_email', 'email_change_token']);
    });
}
};
