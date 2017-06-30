<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWasteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_items', function (Blueprint $table) {
            $table->integer('waste_id')->unsigned();
            $table->integer('item_id')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('price')->default(0);
            $table->string('type');

            $table->foreign('waste_id')->references('id')->on('wastes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waste_items');
    }
}
