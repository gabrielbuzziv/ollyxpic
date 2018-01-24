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
        $worlds = World::orderBy('name', 'asc')->get();
        $worlds->each(function ($world) {
            $highscores = file_get_contents("https://api.tibiadata.com/v2/highscores/{$world->name}/{$this->argument('type')}.json");
            $highscores = json_decode($highscores);

            array_walk($highscores->highscores->data, function ($highscore) use ($world) {
                $highscore = (array) $highscore;
                $highscore['world'] = $world->id;
                $this->highscores[] = (object) $highscore;
            });
        });

        usort($this->highscores, function ($first, $second) {
            return $first->points ? $first->points < $second->points : $first->level < $second->level;
        });

        $highscores = array_slice($this->highscores, 0, 300);

        array_walk($highscores, function ($highscore, $index) {
            Highscores::create([
                'rank'       => $index + 1,
                'name'       => $highscore->name,
                'vocation'   => $highscore->voc,
                'experience' => $highscore->points,
                'level'      => $highscore->level,
                'world_id'   => $highscore->world,
                'updated_at' => Carbon::now(),
                'type'       => $this->argument('type'),
            ]);
        });
    }
}
