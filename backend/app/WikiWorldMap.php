<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiWorldMap extends Model
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
    protected $table = 'WorldMap';

    /**
     * The hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];
}
