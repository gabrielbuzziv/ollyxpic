<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldMap extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['z', 'image'];

    /**
     * Database table.
     *
     * @var string
     */
    protected $table = 'world_map';

    /**
     * Hidden atributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}
