<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_deaths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned();
            $table->integer('level');
            $table->string('reason');
            $table->longText('involved');
            $table->timestamp('died_at');

            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_deaths');
    }
}
