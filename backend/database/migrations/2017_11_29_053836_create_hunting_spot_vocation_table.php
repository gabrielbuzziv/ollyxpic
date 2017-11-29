<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntingSpotVocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_spot_vocation', function (Blueprint $table) {
            $table->integer('hunting_spot_id')->unsigned();
            $table->integer('vocation_id')->unsigned();

            $table->foreign('hunting_spot_id')->references('id')->on('hunting_spots')->onDelete('cascade');
            $table->foreign('vocation_id')->references('id')->on('vocations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunting_spot_vocation');
    }
}
