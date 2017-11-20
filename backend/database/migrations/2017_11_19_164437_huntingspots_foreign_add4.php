<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HuntingspotsForeignAdd4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('huntingspots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longtext('description');
            $table->integer('exp');
            $table->integer('profit');
            $table->boolean('profitable');
            $table->integer('level');
            $table->string('vocation');
            $table->string('teamspot');
            $table->string('supplies');
            $table->string('valuableloot');
            $table->boolean('approved');
            $table->timestamps();
        });

        Schema::create('creaturedrops', function(Blueprint $table) {
            $table->integer('creatureid')->unsigned()->index();
            $table->integer('itemid')->unsigned()->index();
            $table->float('percentage')->unsigned()->index();
            $table->integer('min')->unsigned()->index();
            $table->integer('max')->unsigned()->index();
            $table->timestamps();
        });

        Schema::create('huntingspots_creature', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('creature_id')->unsigned()->index();
            $table->integer('creature_id2')->unsigned()->index();
            $table->integer('huntingspots_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('huntingspots_creature', function(Blueprint $table) {
            $table->foreign('creature_id')->references('id')->on('creatures');
            $table->foreign('creature_id2')->references('id')->on('creaturedrops');
            $table->foreign('huntingspots_id')->references('id')->on('huntingspots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
