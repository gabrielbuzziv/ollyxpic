<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemBuy extends Model
{
    /**
     * Attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'npc_id', 'value'];

    /**
     * Disabled timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A item buy belongs to an NPC.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function npc()
    {
        return $this->belongsTo(NPC::class);
    }

    /**
     * A item buy belongs to an Item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
