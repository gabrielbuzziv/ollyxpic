<?php

namespace App;

use Carbon\Carbon;
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
        'advance',
        'world_id',
        'type',
        'updated_at',
        'active',
        'migration_id'
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

    /**
     * Return highscores with type magic.
     *
     * @param $query
     * @return mixed
     */
    public function scopeMagic($query)
    {
        return $query->where('type', 'magic');
    }

    /**
     * Return highscores with type sword.
     *
     * @param $query
     * @return mixed
     */
    public function scopeSword($query)
    {
        return $query->where('type', 'sword');
    }

    /**
     * Return highscores with type axe.
     *
     * @param $query
     * @return mixed
     */
    public function scopeAxe($query)
    {
        return $query->where('type', 'axe');
    }

    /**
     * Return highscores with type club.
     *
     * @param $query
     * @return mixed
     */
    public function scopeClub($query)
    {
        return $query->where('type', 'club');
    }

    /**
     * Return highscores with type shielding.
     *
     * @param $query
     * @return mixed
     */
    public function scopeShielding($query)
    {
        return $query->where('type', 'shielding');
    }

    /**
     * Return highscores with type distance.
     *
     * @param $query
     * @return mixed
     */
    public function scopeDistance($query)
    {
        return $query->where('type', 'distance');
    }

    /**
     * A highscore belongs to a world.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function world()
    {
        return $this->belongsTo(World::class);
    }
}
