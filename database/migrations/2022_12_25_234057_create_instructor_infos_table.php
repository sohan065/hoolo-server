<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('instructor_uuid')->unique();
            $table->string('full_name');
            $table->string('user_name');
            $table->mediumText('about_me');
            $table->string('media_name');
            $table->string('media_link');
            $table->string('country_uuid');
            $table->string('state_uuid');
            $table->string('city_uuid');
            $table->string('thana_uuid')->nullable();
            $table->string('post_code_uuid');
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
        Schema::dropIfExists('instructor_infos');
    }
}
