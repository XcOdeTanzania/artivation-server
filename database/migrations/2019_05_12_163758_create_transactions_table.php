<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('amount');
            $table->integer('tax');
            $table->integer('shipping_cost');
            $table->integer('discount')->default(0);
            $table->string('status');
            $table->string('tracking_id');
            $table->string('reference_no');
            $table->string('payment_method');
            $table->string('currency')->default('TZS');
            $table->string('items_purchased');
            $table->integer('coupon_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
