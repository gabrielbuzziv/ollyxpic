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
    protected $fillable = ['guild_id', 'character', 'level', 'vocation', 'type'];

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
