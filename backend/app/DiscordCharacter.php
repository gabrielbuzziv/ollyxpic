<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordCharacter extends Model
{

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'discord_characters';

    /**
     * The attributes that can be assigned.
     *
     * @var array
     */
    protected $fillable = ['guild_id', 'character', 'level', 'vocation', 'type', 'online', 'time_online'];

    /**
     * Get friends only.
     *
     * @param $query
     * @return mixed
     */
    public function scopeFriends($query)
    {
        $query->where('type', 'friend');
    }

    /**
     * Get friends only.
     *
     * @param $query
     * @return mixed
     */
    public function scopeEnemies($query)
    {
        $query->where('type', 'enemy');
    }

    /**
     * A character belongs to a guild;
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guild()
    {
        return $this->belongsTo(DiscordGuild::class);
    }
}
