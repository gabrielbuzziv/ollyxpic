<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creature extends Model
{

    /**
     * Database connections.
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Hidden in collection.
     *
     * @var array
     */
    protected $hidden = ['image'];
}
