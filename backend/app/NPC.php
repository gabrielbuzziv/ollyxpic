<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NPC extends Model
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
    protected $table = 'NPCs';

    /**
     * The attributes that is hidden in collection.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Items the NPC Sell
     *
     * @return $this
     */
    public function sells()
    {
        return $this->belongsToMany(Items::class, 'SellItems', 'vendorid', 'itemid')->withPivot('value');
    }
    
}
