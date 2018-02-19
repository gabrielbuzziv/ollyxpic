<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighscoresSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highscores_skills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rank');
            $table->string('name');
            $table->string('vocation');
            $table->integer('skill');
            $table->integer('world_id')->unsigned();
            $table->string('type');
            $table->date('updated_at');
            $table->boolean('active');
            $table->integer('migration_id')->unsigned();

            $table->foreign('world_id')->references('id')->on('worlds')->onDelete('cascade');
            $table->foreign('migration_id')->references('id')->on('highscores_migrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('highscores_skills');
    }
}
