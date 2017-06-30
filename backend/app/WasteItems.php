<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WasteItems extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['waste_id', 'item_id', 'quantity', 'price', 'type'];

    /**
     * The attributes that is appended to collection.
     *
     * @var array
     */
    protected $appends = ['data'];

    /**
     * Set timestamp to false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the item object from database.
     *
     * @return mixed
     */
    public function getDataAttribute()
    {
        return Items::find($this->item_id);
    }
}
