<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MVPPlayer extends Model
{
    /**
     * Database connection.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Database connection.
     *
     * @var string
     */
    protected $table = 'mvp_players';

    /**
     * The attributes that can be assigned.
     *
     * @var array
     */
    protected $fillable = ['mvp_id', 'player', 'experience', 'besthit'];
}
