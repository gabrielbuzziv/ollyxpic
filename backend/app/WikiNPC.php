<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiNPC extends Model
{
    /**
     * Database connection.
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'NPCs';

    /**
     * The attributes that is hidden in collection.
     *
     * @var array
     */
    protected $hidden = ['image'];
}
