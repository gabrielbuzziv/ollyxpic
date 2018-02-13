<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToHighscores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('highscores', function (Blueprint $table) {
            $table->index('name');
            $table->index('experience');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('highscores', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['experience']);
        });
    }
}
