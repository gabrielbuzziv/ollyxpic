<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLootTotalInHuntsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hunts', function (Blueprint $table) {
            $table->integer('loot_total')->default(0)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hunts', function (Blueprint $table) {
            $table->integer('loot_total')->default(0)->nullable(false)->change();
        });
    }
}
