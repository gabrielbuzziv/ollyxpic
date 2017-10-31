<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('loot');
            $table->boolean('effective');
            $table->boolean('stackable');
            $table->boolean('goldcoins');
            $table->integer('valuables')->nullable()->default(0);
            $table->integer('loot_total')->nullable(true)->default(0);
            $table->integer('waste_total')->nullable()->default(0);
            $table->string('password');
            $table->string('owner');
            $table->timestamps();
        });

        // Seed tiles here.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hunts');
    }
}
