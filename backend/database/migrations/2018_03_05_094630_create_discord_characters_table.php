<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscordCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_characters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('guild_id')->unsigned();
            $table->string('character');
            $table->integer('level');
            $table->string('vocation');
            $table->char('type'); // A = Ally | E = Enemy
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
        Schema::dropIfExists('discord_characters');
    }
}
