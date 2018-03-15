<?php

namespace App\Jobs;

use App\Events\CharactersDiedEvent;
use App\Events\CharactersLevelUpEvent;
use App\Ollyxpic\Character;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CharactersChangedJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $guild;

    /**
     * @var
     */
    protected $characters;

    /**
     * @var
     */
    protected $onlines;

    /**
     * CharactersChangedJob constructor.
     * @param $guild
     * @param $characters
     * @param $onlines
     */
    public function __construct($guild, $characters, $onlines)
    {
        $this->guild = $guild;
        $this->characters = $characters;
        $this->onlines = $onlines;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $levelAnnounce = [];
        $deathAnnounce = [];

        foreach ($this->onlines as $online) {
            $online = (object) $online;
            $character = $this->guild->characters->where('character', $online->character)->first();
            $profile = (new Character())->check($character->character);

            if ($character->level < $online->level) {
                $levelAnnounce[] = [
                    'character' => $profile['details'],
                    'from'      => $character->level,
                    'to'        => $online->level,
                    'type' => $character->type
                ];

                $character->level = $online->level;
                $character->save();
            }

            if (! empty($profile['deaths'])) {
                $lastDeath = Carbon::createFromFormat('Y-m-d H:i:s', $character->last_death);
                $recentDeath = Carbon::createFromFormat('Y-m-d H:i:s', $profile['deaths'][0]['date'], 'Europe/Berlin')->timezone('America/New_York');

                if ($recentDeath->diffInSeconds($lastDeath) > 0) {
                    $death = $profile['deaths'][0];

                    $deathAnnounce[] = ['character' => $profile['details'], 'death' => $death, 'type' => $character->type];

                    $character->level = $profile['details']['level'];
                    $character->last_death = $recentDeath;
                    $character->save();
                }
            }
        }

        event(new CharactersLevelUpEvent($this->guild->guild_id, $levelAnnounce));
        event(new CharactersDiedEvent($this->guild->guild_id, $deathAnnounce));
    }
}
