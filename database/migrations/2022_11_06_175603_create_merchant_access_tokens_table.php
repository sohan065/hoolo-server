<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_uuid');
            $table->string('os');
            $table->string('browser');
            $table->string('ip_address');
            $table->string('mac_address');
            $table->text('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_access_tokens');
    }
}
