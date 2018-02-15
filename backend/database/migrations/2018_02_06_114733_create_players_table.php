<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('former_names')->nullable();
            $table->string('vocation');
            $table->integer('level');
            $table->string('residence');
            $table->string('house')->nullable();
            $table->string('gender');
            $table->string('married_to')->nullable();
            $table->string('guild')->nullable();
            $table->boolean('premium');
            $table->string('achievements');
            $table->integer('world_id')->unsigned();
            $table->timestamp('last_login');
            $table->timestamps();

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
        Schema::dropIfExists('players');
    }
}
