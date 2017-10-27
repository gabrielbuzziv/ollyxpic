<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['slot', 'name', 'slug', 'categories', 'image'];

    /**
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Unserialize categories.
     *
     * @param $value
     * @return mixed
     */
    public function getCategoriesAttribute($value)
    {
        return unserialize($value);
    }
}
