<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_details', function (Blueprint $table) {

            $table->id();
            $table->string('uuid')->unique();
            $table->string('workshop_uuid');
            $table->double('price', 8, 2);
            $table->double('discount', 8, 2)->nullable();
            $table->string('discount_duration')->nullable();
            $table->smallInteger('discount_type')->nullable();
            $table->string('language');
            $table->string('level');
            $table->text('summary');
            $table->string('cover');
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
        Schema::dropIfExists('workshop_details');
    }
}
