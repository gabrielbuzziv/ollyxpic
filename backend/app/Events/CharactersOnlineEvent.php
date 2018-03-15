<?php

namespace App\Events;

use App\DiscordCharacter;
use App\Highscores;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CharactersOnlineEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Guild ID
     *
     * @var
     */
    public $guild;

    /**
     * Friends List
     *
     * @var
     */
    public $characters;

    /**
     * CharactersOnlineEvent constructor.
     * @param $guild
     */
    public function __construct($guild)
    {
        $this->guild = $guild;
        $this->characters = (new DiscordCharacter)->where('guild_id', $guild)->get();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('discord');
    }
}
