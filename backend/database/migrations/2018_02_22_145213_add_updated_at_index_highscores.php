<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedAtIndexHighscores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('highscores', function (Blueprint $table) {
            $table->dropIndex(['updated_at']);
            $table->index(['updated_at']);
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
            $table->dropIndex(['updated_at']);
        });
    }
}
