<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerDeaths extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'player_id', 'level', 'reason', 'involved', 'died_at', 'type'
    ];

    /**
     * Timestamps attributes.
     *
     * @var array
     */
    protected $dates = ['died_at'];

    /**
     * Timestamps disabled.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get involved list.
     *
     * @param $value
     * @return mixed
     */
    public function getInvolvedAttribute($value)
    {
        return unserialize($value);
    }
    
    /**
     * A death belongs to a penalty.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
