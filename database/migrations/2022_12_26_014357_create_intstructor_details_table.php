<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntstructorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intstructor_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('instructor_uuid')->unique();
            $table->string('dp_category_uuid');
            $table->smallInteger('frequency');
            $table->string('class_type');
            $table->string('area_of_expertice');
            $table->json('certification');
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
        Schema::dropIfExists('intstructor_details');
    }
}
