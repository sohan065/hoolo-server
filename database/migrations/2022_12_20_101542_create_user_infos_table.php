<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('user_uuid')->unique();
            $table->string('full_name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('country_uuid')->nullable();
            $table->string('state_uuid')->nullable();
            $table->string('city_uuid')->nullable();
            $table->string('thana_uuid')->nullable();
            $table->string('post_code_uuid')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('user_infos');
    }
}
