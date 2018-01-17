<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiOutfitImages extends Model
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
    protected $table = 'OutfitImages';

    /**
     * The hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['image'];
}
