<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('invoice_id')->unsigned();
            $table->string('custom_info');
            $table->float('sum');
            $table->float('order_sum');
            $table->string('signature_ex');
            $table->integer('error_code');
            $table->string('debug_message');
            $table->timestamp('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_payment');
    }
}
