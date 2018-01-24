<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighscoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highscores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rank');
            $table->string('name');
            $table->string('vocation');
            $table->integer('experience');
            $table->integer('level');
            $table->integer('world_id')->unsigned();
            $table->string('type');
            $table->timestamp('updated_at');

            $table->foreign('world_id')->references('id')->on('worlds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('highscores');
    }
}
