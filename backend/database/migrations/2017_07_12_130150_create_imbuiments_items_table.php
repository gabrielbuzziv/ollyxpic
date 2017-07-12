<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImbuimentsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imbuiments_items', function (Blueprint $table) {
            $table->integer('item_id');
            $table->integer('imbuiment_id')->unsigned();
            $table->integer('tier');
            $table->integer('amount');

            $table->foreign('imbuiment_id')->references('id')->on('imbuiments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imbuiments_items');
    }
}
