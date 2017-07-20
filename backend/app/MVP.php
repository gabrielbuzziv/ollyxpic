<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MVP extends Model
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
    protected $table = 'mvps';

    /**
     * The attributes that can be assigned.
     *
     * @var array
     */
    protected $fillable = ['title', 'log'];

    /**
     * With collection.
     *
     * @var array
     */
    protected $with = ['players'];

    /**
     * Players
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany(MVPPlayer::class, 'mvp_id');
    }
}
