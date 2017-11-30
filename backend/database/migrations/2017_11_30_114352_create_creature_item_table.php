<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreatureItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creature_item', function (Blueprint $table) {
            $table->integer('creature_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();

            $table->foreign('creature_id')->references('id')->on('creatures')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creature_item');
    }
}
