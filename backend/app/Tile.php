<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{

    /**
     * The attributes that can be assing.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'friction', 'object_id'];

    /**
     * Disable timestamp.
     *
     * @var bool
     */
    public $timestamps = false;
}
