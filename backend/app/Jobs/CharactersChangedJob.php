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
            if (in_array(strtolower($character['character']), $this->getCharactersNames($this->onlines)) === true) {
                $index = $this->getOnlineIndex($character['character']);
                $online = $this->onlines[$index];
                $information = (new Character($character['character']))->run();

                if ($character['level'] < (int) $online['level']) {
                    $levelUpAnnounces[] = [
                        'character' => $information['details'],
                        'from'      => $character['level'],
                        'to'        => (int) $online['level']
                    ];

                    $this->guild->characters()->where('character', $information['details']['name'])->update([
                        'level' => (int) $online['level']
                    ]);
                }

                if ( ! empty($information['deaths'])) {
                    $lastDeath = Carbon::createFromFormat('Y-m-d H:i:s', $character['last_death']);
                    $recentDeath = Carbon::createFromFormat('Y-m-d H:i:s', $information['deaths'][0]['date'], 'Europe/Berlin')->timezone('America/New_York');

                    if ($recentDeath->diffInSeconds($lastDeath) > 0) {
                        $character = $information['details'];
                        $death = $information['deaths'][0];

                        if ($death['type'] != 'arena') {
                            $deathsAnnounces[] = ['character' => $character, 'death' => $death];
                        }

                        $this->guild->characters()->where('character', $character['name'])
                            ->update([
                                'level' => $character['level'],
                                'last_death' => $recentDeath
                            ]);
                    }
                }
            }
        }

        event(new CharactersDiedEvent($this->guild->guild_id, $deathsAnnounces, $this->type));
        event(new CharactersLevelUpEvent($this->guild->guild_id, $levelUpAnnounces, $this->type));
    }

    /**
     * Get the index of player from online list.
     *
     * @param $character
     * @return false|int|string
     */
    private function getOnlineIndex($character)
    {
        return array_search($character, $this->getCharactersNames($this->onlines));
    }

    /**
     * Format the character list only showing character names.
     *
     * @param $characters
     * @return array
     */
    private function getCharactersNames($characters)
    {
        return array_map(function ($character) {
            return $this->clearString(strtolower($character['character']));
        }, $characters);
    }

    /**
     * Remove invisible chars from string.
     *
     * @param $string
     * @return mixed
     */
    private function clearString($string)
    {
        $string = preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', trim($string));

        return preg_replace('/\s+/', ' ', $string);
    }
}
