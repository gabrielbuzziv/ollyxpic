<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
        });

        DB::table('vocations')->insert(['title' => 'Knight']);
        DB::table('vocations')->insert(['title' => 'Druid']);
        DB::table('vocations')->insert(['title' => 'Sorcerer']);
        DB::table('vocations')->insert(['title' => 'Paladin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vocations');
    }
}
