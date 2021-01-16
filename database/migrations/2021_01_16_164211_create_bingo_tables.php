<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBingoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('winner')->default(0);
            $table->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('user');
        });

        Schema::create('card_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->string('column');
            $table->integer('card_id')->unsigned();
            $table->timestamps();

            $table->foreign('card_id')
                ->references('id')->on('cards');
        });

        Schema::create('used_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
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
        Schema::dropIfExists('bingo_tables');
    }
}
