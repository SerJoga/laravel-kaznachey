<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency');
            $table->string('lang');
            $table->integer('payment_system_id');
            $table->integer('product_id')->default(0);
            $table->integer('user_id')->unsigned();
            $table->string('merchant_guid');
            $table->string('signature');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_invoice');
    }
}
