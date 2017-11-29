<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntingSpotCreatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_spot_creature', function (Blueprint $table) {
            $table->integer('hunting_spot_id')->unsigned();
            $table->integer('creature_id')->unsigned();

            $table->foreign('hunting_spot_id')->references('id')->on('hunting_spots')->onDelete('cascade');
            $table->foreign('creature_id')->references('id')->on('creatures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunting_spot_creature');
    }
}
