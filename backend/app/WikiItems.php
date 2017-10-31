<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiItems extends Model
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
     * A WikiItem has many WikiItemProperties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(WikiItemProperties::class, 'itemid');
    }

    /**
     * A WikiItem has many WikiItemSells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sells()
    {
        return $this->hasMany(WikiItemSell::class, 'itemid');
    }

    /**
     * A WikiItem has many WikiItemBuys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buys()
    {
        return $this->hasMany(WikiItemBuy::class, 'itemid');
    }

}
