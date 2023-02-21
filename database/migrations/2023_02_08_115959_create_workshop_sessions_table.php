<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('workshop_uuid');
            $table->json('details');
            $table->json('schedule');
            $table->json('session_title');
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
        Schema::dropIfExists('workshop_sessions');
    }
}
