<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_withdrawal_comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('withdrawal_id')->unsigned();
            $table->text('comment');
            $table->timestamps();

            $table->foreign('withdrawal_id')
                ->references('id')->on('billing_withdrawal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_withdrawal_comment');
    }
}
