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
            $table->bigInteger('guild_id')->unsigned();
            $table->string('character');
            $table->integer('level');
            $table->string('vocation');
            $table->string('world');
            $table->char('type'); // friends/enemies
            $table->boolean('online')->default(0);
            $table->timestamp('last_death')->default(\Carbon\Carbon::now());
            $table->timestamps();

            $table->foreign('guild_id')->references('guild_id')->on('discord_guilds')->onDelete('cascade');
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
