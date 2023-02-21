<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('merchant_uuid')->unique();
            $table->string('country_uuid');
            $table->string('state_uuid');
            $table->string('city_uuid');
            $table->string('thana_uuid')->nullable();
            $table->string('post_code_uuid');
            $table->mediumText('about');
            $table->string('company_name');
            $table->string('company_logo');
            $table->string('company_banner')->nullable();
            $table->string('website')->nullable();
            $table->boolean('status');
            $table->boolean('featured')->nullable();
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
        Schema::dropIfExists('merchant_infos');
    }
}
