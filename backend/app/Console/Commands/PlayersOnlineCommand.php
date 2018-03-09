<?php

namespace App\Console\Commands;

use App\DiscordCharacter;
use App\DiscordGuild;
use App\Events\CharactersDiedEvent;
use App\Events\CharactersOnlineEvent;
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
            $this->onlines = [];
            $type = $this->argument('type');
            $worlds = $guild->characters()->select('world')->$type()->groupBy('world')->get();
            $characters = $guild->characters()->$type()->get()->toArray();
            $charactersName = $this->getCharactersNames($characters);

            $worlds->each(function ($world) use ($charactersName) {
                $onlines = array_filter($this->getOnlinesFromWorld($world->world), function ($character) use ($charactersName) {
                    return in_array($character['character'], $charactersName);
                });
                $this->onlines = array_merge($this->onlines, $onlines);
            });

            $onlines = $this->getCharactersNames($this->onlines);
            $guild->characters()->$type()->whereIn('character', $onlines)->update(['online' => 1]);
            $this->getCharacterChanges($guild, $characters, $this->onlines);

            event(new CharactersOnlineEvent($guild->guild_id, $type));
        });
    }

    private function getCharacterChanges($guild, $characters, $online)
    {
        $onlineNames = $this->getCharactersNames($online);
        $deathsAnnounces = [];

        foreach ($characters as $character) {
            if (in_array($character['character'], $onlineNames) === true) {
                $information = (new Character($character['character']))->run();
                $this->info($character['character']);

                if (! empty($information['deaths'])) {
                    $lastDeath = Carbon::createFromFormat('Y-m-d H:i:s', $character['last_death']);
                    $newDeath = Carbon::createFromFormat('Y-m-d H:i:s', $information['deaths'][0]['date'], 'Europe/Berlin')->timezone('America/New_York');

                    if ($lastDeath->diffInSeconds($newDeath) > 0) {
                        $character = $information['details'];
                        $death = $information['deaths'][0];

                        $deathsAnnounces[] = ['character' => $character, 'death' => $death];
                    }
                }
            }
        }

        event(new CharactersDiedEvent($guild->guild_id, $deathsAnnounces, $this->argument('type')));
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
