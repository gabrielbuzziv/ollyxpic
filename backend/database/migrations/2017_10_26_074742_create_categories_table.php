<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slot');
            $table->string('name');
            $table->string('slug');
            $table->string('categories');
            $table->string('image');
            $table->timestamps();
        });

        $categories = [
            ['slot' => 'weapon', 'name' => 'Axe', 'slug' => 'axe', 'image' => '', 'categories' => serialize(['Axe Weapons'])],
            ['slot' => 'weapon', 'name' => 'Sword', 'slug' => 'sword', 'image' => '', 'categories' => serialize(['Sword Weapons'])],
            ['slot' => 'weapon', 'name' => 'Club', 'slug' => 'club', 'image' => '', 'categories' => serialize(['Club Weapons'])],
            ['slot' => 'weapon', 'name' => 'Distance', 'slug' => 'distance', 'image' => '', 'categories' => serialize(['Distance Weapons'])],
            ['slot' => 'weapon', 'name' => 'Wand & Rods', 'slug' => 'wand', 'image' => '', 'categories' => serialize(['Wands', 'Rods'])],
            ['slot' => 'helmet', 'name' => 'Helmets', 'slug' => 'helmet', 'image' => '', 'categories' => serialize(['Helmets'])],
            ['slot' => 'armor', 'name' => 'Armors', 'slug' => 'armor', 'image' => '', 'categories' => serialize(['Armors'])],
            ['slot' => 'legs', 'name' => 'Legs', 'slug' => 'legs', 'image' => '', 'categories' => serialize(['Legs'])],
            ['slot' => 'boots', 'name' => 'Boots', 'slug' => 'boots', 'image' => '', 'categories' => serialize(['Boots'])],
            ['slot' => 'ring', 'name' => 'Rings', 'slug' => 'ring', 'image' => '', 'categories' => serialize(['Rings'])],
            ['slot' => 'amulet', 'name' => 'Amulets', 'slug' => 'amulet', 'image' => '', 'categories' => serialize(['Amulets and Necklaces'])],
            ['slot' => 'backpack', 'name' => 'Backpack', 'slug' => 'backpack', 'image' => '', 'categories' => serialize(['Containers'])],
            ['slot' => 'shield', 'name' => 'Shields', 'slug' => 'shield', 'image' => '', 'categories' => serialize(['Shields', 'Spellbooks'])],
            ['slot' => 'ammunition', 'name' => 'Ammunition', 'slug' => 'ammunition', 'image' => '', 'categories' => serialize(['Ammunition'])],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'slot'       => $category['slot'],
                'name'       => $category['name'],
                'slug'       => $category['slug'],
                'image'      => $category['image'],
                'categories' => $category['categories'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
