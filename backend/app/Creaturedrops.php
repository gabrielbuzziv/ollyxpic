<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creaturedrops extends Model
{
    /**
     * The attributes that can be assign.
     *
     * @var array
     */
	protected $fillable= [
		'id',
	    'creatureid'

	];


	/**
	 * 
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function itemdropss()
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
