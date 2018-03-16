<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlineTimeAndIndexesToDiscordCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discord_characters', function (Blueprint $table) {
            $table->integer('time_online')->default(0);
            $table->index('guild_id');
            $table->index('character');
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
            $table->dropColumn('time_online');
            $table->dropIndex(['guild_id']);
            $table->dropIndex(['character']);
        });
    }
}
