<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldMap extends Model
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

}
