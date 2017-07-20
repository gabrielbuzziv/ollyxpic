<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
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
    protected $table = 'Items';

    /**
     * The hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Join relationship in collection.
     *
     * @var array
     */
    protected $with = ['props', 'sellTo', 'buyFrom'];

    /**
     * Can be sell to
     */
    public function sellTo()
    {
        return $this->belongsToMany(NPC::class, 'SellItems', 'itemid', 'vendorid')->withPivot('value');
    }

    /**
     * Can be bought from
     *
     * @return $this
     */
    public function buyFrom()
    {
        return $this->belongsToMany(NPC::class, 'BuyItems', 'itemid', 'vendorid')->withPivot('value');
    }

    /**
     * Props
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function props()
    {
        return $this->hasMany(ItemProps::class, 'item_id');
    }
}
