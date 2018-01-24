<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Highscores extends Model
{
    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'rank',
        'name',
        'vocation',
        'experience',
        'level',
        'world_id',
        'type',
        'updated_at',
    ];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return highscores with type experience.
     *
     * @param $query
     * @return mixed
     */
    public function scopeExperience($query)
    {
        return $query->where('type', 'experience');
    }
}
