<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['potions', 'ammunitions', 'runes', 'amulets', 'rings', 'total'];

    /**
     * The relationship joined in collection.
     *
     * @var array
     */
    protected $with = ['items'];

    /**
     * Has many items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(WasteItems::class);
    }
}
