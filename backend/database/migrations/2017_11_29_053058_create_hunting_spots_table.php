<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuntingSpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_spots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->longText('tips')->nullable();
            $table->integer('experience');
            $table->integer('profit')->nullable();
            $table->integer('level_min');
            $table->integer('level_max');
            $table->boolean('require_premium')->default(true);
            $table->boolean('require_quest')->default(false);
            $table->boolean('has_task')->default(false);
            $table->boolean('soloable')->default(true);
            $table->string('author')->nullable();
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
        Schema::dropIfExists('hunting_spots');
    }
}
