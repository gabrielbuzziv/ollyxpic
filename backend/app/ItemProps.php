<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemProps extends Model
{

    /**
     * The attribute that can be assigned.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'description', 'value'];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'item_props';

    /**
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Has one item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item()
    {
        return $this->hasOne(Items::class);
    }

}
