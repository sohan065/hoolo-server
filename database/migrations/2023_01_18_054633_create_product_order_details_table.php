<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('user_uuid');
            $table->string('order_code');
            $table->string('address');
            $table->string('post_code');
            $table->string('thana')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->double('shipping_cost',8,2)->nullable();
            $table->string('phone');
            $table->string('name');
            $table->smallInteger('order_status');
            $table->smallInteger('delivery_status');
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
        Schema::dropIfExists('product_order_details');
    }
}
