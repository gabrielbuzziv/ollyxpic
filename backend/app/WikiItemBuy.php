<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiItemBuy extends Model
{
    /**
     * Database connection.
     *
     * @var string
     */
    protected $connection = 'sqlite';

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'BuyItems';

    /**
     * A WikiItemBuy belongs to a WikiItems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(WikiItems::class, 'itemid');
    }

    /**
     * A WikiItemBuy belongs to a WikiNPC
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function npc()
    {
        return $this->belongsTo(WikiNPC::class, 'vendorid');
    }
}
