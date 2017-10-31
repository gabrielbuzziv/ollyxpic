<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['title', 'name', 'image', 'usable', 'slot'];

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
     * A category has many items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
