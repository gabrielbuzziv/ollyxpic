<?php

namespace App\Jobs;

use App\Events\CharactersDiedEvent;
use App\Events\CharactersLevelUpEvent;
use App\Ollyxpic\Crawlers\Character;
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
     * @var
     */
    protected $type;

    /**
     * CharactersChangedJob constructor.
     * @param $guild
     * @param $characters
     * @param $onlines
     * @param $type
     */
    public function __construct($guild, $characters, $onlines, $type)
    {
        $this->guild = $guild;
        $this->characters = $characters;
        $this->onlines = $onlines;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $levelUpAnnounces = [];
        $deathsAnnounces = [];

        foreach ($this->characters as $character) {
            if (in_array($character['character'], $this->onlines) === true) {
                $information = (new Character($character['character']))->run();

                if ($character['level'] < $information['details']['level']) {
                    $levelUpAnnounces[] = [
                        'character' => $information['details'],
                        'from'      => $character['level'],
                        'to'        => $information['details']['level']
                    ];
                }

                if ( ! empty($information['deaths'])) {
                    $lastDeath = Carbon::createFromFormat('Y-m-d H:i:s', $character['last_death']);
                    $newDeath = Carbon::createFromFormat('Y-m-d H:i:s', $information['deaths'][0]['date'], 'Europe/Berlin')->timezone('America/New_York');

                    if ($newDeath->diffInSeconds($lastDeath) > 0) {
                        $character = $information['details'];
                        $death = $information['deaths'][0];

                        if ($death['type'] != 'arena') {
                            $deathsAnnounces[] = ['character' => $character, 'death' => $death];
                            $this->guild->characters()
                                ->where('character', $character['name'])
                                ->update(['last_death' => $newDeath]);
                        }
                    }
                }
            }
        }

        event(new CharactersDiedEvent($this->guild->guild_id, $deathsAnnounces, $this->type));
        event(new CharactersLevelUpEvent($this->guild->guild_id, $levelUpAnnounces, $this->type));
    }
}
