<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMvpPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvp_players', function (Blueprint $table) {
            $table->integer('mvp_id')->unsigned();
            $table->string('player');
            $table->string('experience');
            $table->string('besthit');

            $table->foreign('mvp_id')->references('id')->on('mvps')->onDelete('cascade');
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
        Schema::dropIfExists('mvp_players');
    }
}
