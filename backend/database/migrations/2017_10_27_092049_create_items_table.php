<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('name');
            $table->integer('vendor_value')->nullable();
            $table->integer('actual_value')->nullable();
            $table->float('capacity')->nullable();
            $table->boolean('stackable');
            $table->binary('image');
            $table->integer('category_id')->unsigned();
            $table->boolean('discard');
            $table->boolean('convert_to_gold');
            $table->longText('look_text');
            $table->boolean('usable')->default(false);

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
