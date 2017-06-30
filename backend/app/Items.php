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
     * The appended attributes.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * Join relationship in collection.
     *
     * @var array
     */
    protected $with = ['properties', 'sellTo'];

    /**
     * Return image rendered url.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return action('ImageController@load', str_slug($this->title));
    }

    /**
     * Item Properties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(ItemProperties::class, 'itemid');
    }

    /**
     * Can be sell to
     */
    public function sellTo()
    {
        return $this->belongsToMany(NPC::class, 'SellItems', 'itemid', 'vendorid')->withPivot('value');
    }
}
