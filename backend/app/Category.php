<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'items_categories';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'item_id';

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'category', 'usable'];

    /**
     * @var array
     */
    protected $with = ['item'];

    /**
     * Has many Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item()
    {
        return $this->hasOne(Items::class, 'id');
    }
}
