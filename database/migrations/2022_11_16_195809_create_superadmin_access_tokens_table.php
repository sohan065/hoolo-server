<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperadminAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superadmin_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('superadmin_uuid');
            $table->string('os');
            $table->string('browser');
            $table->string('ip_address');
            $table->string('mac_address');
            $table->text('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('superadmin_access_tokens');
    }
}
