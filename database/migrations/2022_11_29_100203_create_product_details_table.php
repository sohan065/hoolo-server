<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->string('product_uuid');
            $table->smallInteger('stock');
            $table->string('tags')->nullable();
            $table->double('price', 8, 2);
            $table->double('discount', 8, 2)->nullable();
            $table->smallInteger('discount_type')->nullable();
            $table->string('discount_duration')->nullable();
            $table->longText('details');
            $table->string('cover');
            $table->json('images');
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
        Schema::dropIfExists('product_details');
    }
}
