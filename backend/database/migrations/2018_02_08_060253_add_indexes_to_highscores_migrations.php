<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToHighscoresMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('highscores_migrations', function (Blueprint $table) {
            $table->index('type');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('highscores_migrations', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['updated_at']);
        });
    }
}
