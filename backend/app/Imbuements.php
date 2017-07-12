<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imbuements extends Model
{

    protected $table = 'imbuiments';

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['title', 'name', 'description'];

    /**
     * @var array]
     */
    protected $with = ['items'];

    /**
     * Items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ImbuementItems::class, 'imbuiment_id');
    }
}
