<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tiles extends Model
{

    /**
     * The attributes that can be assing.
     *
     * @var array
     */
    protected $fillable = ['name', 'friction', 'object_id'];
}
