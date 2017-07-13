<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImbuementItems extends Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'imbuiments_items';

    /**
     * With Item
     *
     * @var array
     */
    protected $with = ['item'];

    /**
     * Has Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item()
    {
        return $this->hasOne(Items::class, 'id', 'item_id');
    }

}
