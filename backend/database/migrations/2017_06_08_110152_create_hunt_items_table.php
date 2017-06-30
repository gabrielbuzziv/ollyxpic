<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunt_items', function (Blueprint $table) {
            $table->integer('hunt_id')->unsigned();
            $table->integer('item_id');
            $table->integer('quantity');
            $table->integer('unit_price');

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
        Schema::dropIfExists('hunt_items');
    }
}
