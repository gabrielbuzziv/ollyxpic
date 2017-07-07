<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldObject extends Model
{

    /**
     * Connection with database
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Database table.
     *
     * @var string
     */
    protected $table = 'WorldObjects';

    /**
     * @var array
     */
    protected $fillable = ['id'];

    /**
     * Hidden from collection
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Set timestamp to false.
     *
     * @var bool
     */
    public $timestamps = false;
}
