<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntingSpotEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_spot_equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hunting_spot_id')->unsigned();
            $table->string('title');
            $table->longText('description');
            $table->integer('amulet_id')->unsigned();
            $table->integer('helmet_id')->unsigned();
            $table->integer('weapon_id')->unsigned();
            $table->integer('armor_id')->unsigned();
            $table->integer('shield_id')->unsigned();
            $table->integer('ring_id')->unsigned();
            $table->integer('boots_id')->unsigned();
            $table->integer('ammunition_id')->unsigned();

            $table->foreign('hunting_spot_id')->references('id')->on('hunting_spots')->onDelete('cascade');
            $table->foreign('amulet_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('helmet_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('weapon_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('armor_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('shield_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('ring_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('boots_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('ammunition_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunting_spot_equipments');
    }
}
