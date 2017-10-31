<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class World extends Model
{
    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['name', 'type'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currencies()
    {
        return $this->hasMany(WorldCurrency::class)->latest();
    }
}
