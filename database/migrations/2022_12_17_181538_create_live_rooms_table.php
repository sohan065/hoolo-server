<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('creator_uuid');
            $table->string('room_id')->unique();
            $table->string('course_uuid');
            $table->boolean('type');
            $table->boolean('ended');
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
        Schema::dropIfExists('live_rooms');
    }
}
