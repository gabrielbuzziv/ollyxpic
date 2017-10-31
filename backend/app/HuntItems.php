<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HuntItems extends Model
{

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'item_id';

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'hunt_id',
        'item_id',
        'quantity',
        'unit_price'
    ];

    /**
     * Disabled timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A item belongs to a hunt.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hunt()
    {
        return $this->belongsTo(Hunts::class);
    }

    /**
     * Items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
