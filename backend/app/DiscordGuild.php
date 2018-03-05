<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordGuild extends Model
{

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'discord_guilds';

    /**
     * The attributes that can be assigned.
     *
     * @var array
     */
    protected $fillable = ['guild_id', 'name'];

    /**
     * A guild has many characters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters()
    {
        return $this->hasMany(DiscordCharacter::class);
    }
}
