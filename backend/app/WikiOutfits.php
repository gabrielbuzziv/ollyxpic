<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiOutfits extends Model
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
    protected $table = 'Outfits';

    /**
     * A Outfit has many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function image()
    {
        return $this->hasMany(WikiOutfitImages::class, 'outfitid')->where('addon', 3)->where('male', true);
    }
}
