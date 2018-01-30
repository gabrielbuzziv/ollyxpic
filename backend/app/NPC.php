<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPC extends Model
{

    /**
     * Database table name.
     *
     * @var string
     */
    protected $table = 'npcs';

    /**
     * Attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'name',
        'city',
        'job',
        'x',
        'y',
        'z',
        'image'
    ];

    /**
     * Hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Disable timestamps;
     *
     * @var bool
     */
    public $timestamps = false;
}
