<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imbuement extends Model
{
    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['title', 'name', 'description'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ImbuementItems::class);
    }
}
