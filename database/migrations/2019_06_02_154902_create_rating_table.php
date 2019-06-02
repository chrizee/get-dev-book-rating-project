<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger("rating");
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("book_id")->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")->references('id')->on('users');
            $table->foreign("book_id")->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
