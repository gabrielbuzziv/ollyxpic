<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntTeammatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunt_teammates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hunt_id')->unsigned();
            $table->string('character');
            $table->integer('waste')->nullable()->default(0);
            $table->integer('profit')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('hunt_id')->references('id')->on('hunts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunt_teammates');
    }
}
