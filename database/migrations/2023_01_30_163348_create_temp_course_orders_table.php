<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempCourseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_course_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('user_uuid');
            $table->string('course_uuid');
            $table->string('payment_id')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_no')->nullable();
            $table->string('refund_ref_id')->nullable();
            $table->string('bank_trans_id')->nullable();
            $table->string('order_code');
            $table->string('trx_id')->nullable();
            $table->string('trx_number')->nullable();
            $table->integer('quantity');
            $table->double('price', 8, 2);
            $table->string('payment_method')->nullable();
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
        Schema::dropIfExists('temp_course_orders');
    }
}
