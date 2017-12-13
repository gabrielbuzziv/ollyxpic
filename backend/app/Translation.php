<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['from', 'to', 'fixed'];

    /**
     * Disable timestamp.
     *
     * @var bool
     */
    public $timestamps = false;
}
