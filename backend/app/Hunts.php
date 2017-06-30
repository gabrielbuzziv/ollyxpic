<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hunts extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'loot',
        'effective',
        'stackable',
        'goldcoins',
        'valuables',
        'loot_total',
        'waste_total',
        'password',
        'owner'
    ];

    /**
     * Return the collection with relationships.
     *
     * @var array
     */
    protected $with = ['items', 'teammates'];

    /**
     * Attribute that will not be shown in collection.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * A hunt has many items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(HuntItems::class, 'hunt_id');
    }

    /**
     * A hunt has many teammates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teammates()
    {
        return $this->hasMany(HuntTeammates::class, 'hunt_id');
    }

}
