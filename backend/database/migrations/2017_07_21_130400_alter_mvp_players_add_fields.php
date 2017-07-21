<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMvpPlayersAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvp_players', function (Blueprint $table) {
            $table->integer('damage')->default(0);
            $table->float('participation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvp_players', function (Blueprint $table) {
            $table->removeColumn('damage');
            $table->removeColumn('participation');
        });
    }
}
