<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    /**
     * Database table name.
     *
     * @var string
     */
    protected $table = 'partners';

    /**
     * Attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'twitch',
        'youtube',
        'site'
    ];
}
