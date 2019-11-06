<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pieces', function (Blueprint $table) {
            $table->increments('id');
             $table->string('image');
             $table->integer('price');
             $table->string('title');
             $table->string('size');
             $table->string('desc');
             $table->double('rate')->nullable();
             $table->integer('artist_id');
             $table->integer('category_id');
             $table->integer('purchased_by')->nullable();
             $table->boolean('cart_status')->nullable();
             $table->text('favorite_list')->nullable();
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
        Schema::dropIfExists('pieces');
    }
}
