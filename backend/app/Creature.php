<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creature extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'name',
        'health',
        'experience',
        'maxdamage',
        'summon',
        'illusionable',
        'pushable',
        'pushes',
        'physical',
        'holy',
        'death',
        'fire',
        'ice',
        'earth',
        'drown',
        'lifedrain',
        'paralysable',
        'senseinvis',
        'image',
        'abilities',
        'speed',
        'armor',
        'boss'
    ];

    /**
     * Hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function creaturess()
    {
        return $this->belongsToMany(Huntingspot::class, 'huntingspots_creature', 'huntingspots_id', 'creature_id');
    }

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}
