<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CharactersLevelUpEvent implements ShouldBroadcast
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
     * Type
     *
     * @var
     */
    public $type;

    /**
     * CharactersLevelUpEvent constructor.
     * @param $guild
     * @param $type
     */
    public function __construct($guild, $type)
    {
        $this->guild = $guild;
        $this->characters = (new DiscordCharacter)->where('guild_id', $guild)->$type()->get();
        $this->type = $type;
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
