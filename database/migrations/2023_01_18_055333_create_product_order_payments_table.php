<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_payments', function (Blueprint $table) {
           $table->id();
            $table->string('uuid')->unique();
            $table->string('user_uuid');
            $table->string('order_code');
            $table->string('payment_method');
            $table->string('trx_number')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('payment_with')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_no')->nullable();
            $table->string('bank_tran_id')->nullable();
            $table->smallInteger('status');
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
        Schema::dropIfExists('product_order_payments');
    }
}
