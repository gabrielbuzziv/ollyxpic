<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    /**
     * Attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'name',
        'vendor_value',
        'actual_value',
        'capacity',
        'stackable',
        'image',
        'category_id',
        'discard',
        'convert_to_gold',
        'look_text',
        'usable'
    ];

    /**
     * Hidden atributes.
     *
     * @var array
     */
    protected $hidden = ['image'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Usable scope.
     *
     * @param $query
     * @return mixed
     */
    public function scopeUsable($query)
    {
        return $query->where('usable', 1);
    }

    /**
     * A item belongs to a category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Properties
     */
    public function properties()
    {
        return $this->hasMany(ItemProperties::class);
    }

    /**
     * Sells
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sells()
    {
        return $this->hasMany(ItemSell::class);
    }

    /**
     * Buys
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buys()
    {
        return $this->hasMany(ItemBuy::class);
    }

}
