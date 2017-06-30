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
     * Attributes appended in collection.
     *
     * @var array
     */
    protected $appends = ['data'];

    /**
     * Disabled timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get item object
     *
     * @return mixed
     */
    public function getDataAttribute()
    {
        return Items::find($this->item_id);
    }

    /**
     * A item belongs to a hunt.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hunt()
    {
        return $this->belongsTo(Hunts::class);
    }
}
