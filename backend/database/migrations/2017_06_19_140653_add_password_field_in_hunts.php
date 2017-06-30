<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordFieldInHunts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hunts', function (Blueprint $table) {
            $table->string('password');
            $table->string('owner');
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
            $table->dropColumn('password');
            $table->dropColumn('owner');
        });
    }
}
