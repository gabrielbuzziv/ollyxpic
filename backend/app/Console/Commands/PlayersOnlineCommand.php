<?php

namespace App\Console\Commands;

use App\DiscordCharacter;
use App\DiscordGuild;
use App\Events\CharactersChangedEvent;
use App\Events\CharactersDiedEvent;
use App\Events\CharactersOnlineEvent;
use App\Jobs\CharactersChangedJob;
use App\Ollyxpic\TibiaData\WorldOnlinesAPI;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Goutte\Client;
use App\Ollyxpic\Crawlers\CharacterCrawler;
use Illuminate\Support\Facades\DB;

class PlayersOnlineCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ollyxpic:online {type=friends}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get online characters from discord.';

    /**
     * Players online.
     *
     * @var
     */
    protected $onlines;

    /**
     * @var
     */
    protected $worlds = [];

    /**
     * PlayersOnlineCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $guilds = (new DiscordGuild)->with('characters')->get();
        $guilds->each(function ($guild) {
            $this->onlines = [];

            $characters = $guild->characters->toArray();
            $worlds = $this->getWorlds($characters);

            foreach ($worlds as $world) {
                $this->onlines = array_merge($this->onlines, $this->getOnlines($world, $this->getCharactersNames($characters)));
            }

            if (count($this->onlines)) {
                dispatch(new CharactersChangedJob($guild, $characters, $this->onlines));
            }

            $guild->characters()->whereIn('character', $this->getCharactersNames($this->onlines))->update([
                'online' => 1,
                'time_online' => DB::raw('time_online + 5')
            ]);
            $guild->characters()->whereNotIn('character', $this->getCharactersNames($this->onlines))->update(['online' => 0, 'time_online' => 0]);

            event(new CharactersOnlineEvent($guild->guild_id));
        });
    }

    /**
     * Get onlines from world.
     *
     * @param $world
     * @param $characters
     * @return array
     */
    private function getOnlines($world, $characters)
    {
        return array_filter($this->getOnlinesFrom($world), function ($online) use ($characters) {
            return in_array($this->clearString($online['character']), $characters);
        });
    }

    /**
     * Get onlines from worlds.
     *
     * @param $world
     * @return mixed
     */
    private function getOnlinesFrom($world)
    {
        if (! isset($this->worlds[$world])) {
            $this->worlds[$world] = (new WorldOnlinesAPI($world))->get();
        }

        return $this->worlds[$world];
    }

    /**
     * Get worlds names from charactes list.
     *
     * @param $characters
     * @return array
     */
    private function getWorlds($characters)
    {
        return array_unique(array_map(function ($character) {
            return $character['world'];
        }, $characters));
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
            return $character['character'];
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
