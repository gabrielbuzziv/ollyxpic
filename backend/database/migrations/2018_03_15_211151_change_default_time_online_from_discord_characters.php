<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultTimeOnlineFromDiscordCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discord_characters', function (Blueprint $table) {
            $table->integer('time_online')->default(-5)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discord_characters', function (Blueprint $table) {
            $table->integer('time_online')->default()->change();
        });
    }
}
