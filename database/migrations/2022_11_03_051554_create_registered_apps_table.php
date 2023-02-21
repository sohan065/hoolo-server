<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_apps', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('app_name')->unique();
            $table->string('domain')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('platform');
            $table->text('app_key');
            $table->boolean('status');
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
        Schema::dropIfExists('registered_apps');
    }
}
