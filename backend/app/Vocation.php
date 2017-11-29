<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vocation extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * Disable timestamp.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A vocation belongs to many hunting spots.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function huntingSpots()
    {
        return $this->belongsToMany(HuntingSpot::class);
    }
}
