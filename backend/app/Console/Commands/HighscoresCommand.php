<?php

namespace App\Console\Commands;

use App\World;
use App\Highscores;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HighscoresCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ollyxpic:highscores {type=experience}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Highscores from all Tibia Worlds.';

    /**
     * Highscores.
     *
     * @var
     */
    protected $highscores;

    /**
     * Highscores constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->highscores = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::today()->subDay();
        (new Highscores)
            ->where('type', $this->argument('type'))
            ->where('updated_at', $date)
            ->delete();

        $vocations = ['knight', 'sorcerer', 'paladin', 'druid'];

        foreach ($vocations as $vocation) {
            $worlds = World::orderBy('name', 'asc')->get();
            $worlds->each(function ($world) use ($vocation, $date) {
                $highscores = file_get_contents("https://api.tibiadata.com/v2/highscores/{$world->name}/{$this->argument('type')}/{$vocation}.json");
                $highscores = json_decode($highscores);
                $highscores = $highscores->highscores->data;

                array_walk($highscores, function ($highscore, $index) use ($world, $date) {
                    $level = $highscore->level;
                    $experience = $this->argument('type') == 'experience' ? $highscore->points : 0;
                    $advance = 0;

                    Highscores::create([
                        'rank'       => $highscore->rank,
                        'name'       => $highscore->name,
                        'vocation'   => $highscore->voc,
                        'experience' => $experience,
                        'level'      => $level,
                        'advance'    => $advance,
                        'world_id'   => $world->id,
                        'updated_at' => $date,
                        'type'       => $this->argument('type'),
                    ]);
                });
            });
        }

        (new Highscores)->where('updated_at', $date)->update(['active', 1]);
    }
}
