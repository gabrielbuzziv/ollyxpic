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
            $table->longText('tips');
            $table->decimal('experience', 10, 2);
            $table->decimal('profit', 10, 2);
            $table->string('place');
            $table->integer('level_min');
            $table->integer('level_max');
            $table->boolean('require_premium');
            $table->boolean('require_quest');
            $table->boolean('has_task');
            $table->boolean('profitable');
            $table->boolean('soloable');
            $table->string('author');
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
