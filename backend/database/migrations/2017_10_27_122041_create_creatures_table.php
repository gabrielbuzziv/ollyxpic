<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creatures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('name');
            $table->integer('health')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('maxdamage')->nullable();
            $table->integer('summon')->nullable();
            $table->boolean('illusionable');
            $table->boolean('pushable');
            $table->boolean('pushes');
            $table->integer('physical')->nullable();
            $table->integer('holy')->nullable();
            $table->integer('death')->nullable();
            $table->integer('fire')->nullable();
            $table->integer('ice')->nullable();
            $table->integer('energy')->nullable();
            $table->integer('earth')->nullable();
            $table->integer('drown')->nullable();
            $table->integer('lifedrain')->nullable();
            $table->boolean('paralysable');
            $table->boolean('senseinvis');
            $table->binary('image');
            $table->longText('abilities');
            $table->integer('speed')->nullable();
            $table->integer('armor')->nullable();
            $table->boolean('boss');
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
        Schema::dropIfExists('creatures');
    }
}
