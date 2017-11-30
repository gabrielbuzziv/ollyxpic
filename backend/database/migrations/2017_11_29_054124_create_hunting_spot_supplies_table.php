<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntingSpotSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_spot_supplies', function (Blueprint $table) {
            $table->integer('hunting_spot_id')->unsigned();
            $table->integer('supply_id')->unsigned();
            $table->integer('amount')->default(1);

            $table->foreign('hunting_spot_id')->references('id')->on('hunting_spots')->onDelete('cascade');
            $table->foreign('supply_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunting_spot_supplies');
    }
}
