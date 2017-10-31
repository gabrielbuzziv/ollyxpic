<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldCurrency extends Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'world_currency';

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['world_id', 'buy', 'sell'];

    /**
     * Currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function world()
    {
        return $this->belongsTo(World::class);
    }
}
