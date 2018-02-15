<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'former_names', 'vocation', 'level', 'residence', 'house', 'gender', 'married_to',
        'guild', 'premium', 'achievements', 'world_id', 'last_login'
    ];

    /**
     * A player belong to a world.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function world()
    {
        return $this->belongsTo(World::class);
    }

    /**
     * A player can have many deaths.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deaths()
    {
        return $this->hasMany(PlayerDeaths::class);
    }
}
