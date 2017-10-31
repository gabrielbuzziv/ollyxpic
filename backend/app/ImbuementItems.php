<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImbuementItems extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'imbuement_id', 'tier', 'amount'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Has Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

}
