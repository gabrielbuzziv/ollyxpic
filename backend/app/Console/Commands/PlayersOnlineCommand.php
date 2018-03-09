<?php

namespace App\Console\Commands;

use App\DiscordCharacter;
use App\DiscordGuild;
use App\Events\CharactersChangedEvent;
use App\Events\CharactersDiedEvent;
use App\Events\CharactersOnlineEvent;
use App\Jobs\CharactersChangedJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Goutte\Client;
use App\Ollyxpic\Crawlers\Character;

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
        $guilds = (new DiscordGuild)->get();
        $guilds->each(function ($guild) {
            $this->info("Guild: {$guild->name}");
            $this->onlines = [];
            $type = $this->argument('type');

            $characters = $guild->characters()->$type()->get()->toArray();
            $charactersName = $this->getCharactersNames($characters);
            $worlds = $this->getWorlds($characters);

            foreach ($worlds as $world) {
                $onlines = array_filter($this->getOnlinesFromWorld($world), function ($character) use ($charactersName) {
                    return in_array($character['character'], $charactersName);
                });
                $this->onlines = array_merge($this->onlines, $onlines);
            }

            foreach ($this->onlines as $online) {
                $guild->characters()->$type()->where('character', $online['character'])->update([
                    'level' => $online['level'],
                    'online' => 1
                ]);
            }

            $totalOnlines = count($this->onlines);
            $this->info("Onlines: {$totalOnlines}");


            $onlines = $this->getCharactersNames($this->onlines);
            $guild->characters()->$type()->whereNotIn('character', $onlines)->update(['online' => 0]);

            event(new CharactersOnlineEvent($guild->guild_id, $type));
            $this->info('Emitted Event: @CharactersOnlineEvent');

            // This event will be queued.
            dispatch(new CharactersChangedJob($guild, $characters, $onlines, $type));
            $this->info('Emitted Event: @CharactersChangedJob');
            $this->info('___________');
        });
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
     * Get players online list from Tibia world online page.
     *
     * @param $world
     * @return array
     */
    private function getOnlinesFromWorld($world)
    {
        $url = "https://secure.tibia.com/community/?subtopic=worlds&world={$world}";

        $client = new Client();
        $crawler = $client->request('GET', $url);

        return array_slice($crawler->filterXPath('//table[@class="Table2"]')->filter('tr')->each(function ($tr) {
            $character = $tr->filter('td')->each(function ($td) {
                return $td->text();
            });

            return ['character' => $character[0], 'level' => $character[1], 'vocation' => $character[2]];
        }), 2);
    }
}
