<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemProperties extends Model
{

    /**
     * Attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'property', 'value'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A item property belongs to an item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
