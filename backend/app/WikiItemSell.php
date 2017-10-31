<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiItemSell extends Model
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
    protected $table = 'SellItems';

    /**
     * A WikiItemSell belongs to a WikiItems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(WikiItems::class, 'itemid');
    }

    /**
     * A WikiItemSell belongs to a WikiNPC
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function npc()
    {
        return $this->belongsTo(WikiNPC::class, 'vendorid');
    }
}
