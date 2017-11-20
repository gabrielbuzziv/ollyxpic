<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Huntingspot extends Model
{

/**
 * 
 *
 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
 */
public function creatures()
{
    return $this->belongsToMany(Creature::class, 'huntingspots_creature', 'huntingspots_id', 'creature_id');
}

/**
 * 
 *
 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
 */
public function itemdrops()
{
    return $this->belongsToMany(Creaturedrops::class, 'huntingspots_creature', 'huntingspots_id', 'creature_id');
}

}
