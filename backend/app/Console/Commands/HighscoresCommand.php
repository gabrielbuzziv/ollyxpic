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
        $vocations = ['knight', 'sorcerer', 'paladin', 'druid'];

        for ($i = 2; $i >= 1; $i --) {
            foreach ($vocations as $vocation) {
                $worlds = World::orderBy('name', 'asc')->get();
                $worlds->each(function ($world) use ($i, $vocation) {
                    $highscores = file_get_contents("https://api.tibiadata.com/v2/highscores/{$world->name}/{$this->argument('type')}/{$vocation}.json");
                    $highscores = json_decode($highscores);
                    $highscores = $highscores->highscores->data;

                    array_walk($highscores, function ($highscore, $index) use ($world, $i) {
                        $today = Carbon::today()->subDays($i);
//                    $experience = $highscore->points;
                        $level = $highscore->level - ($i - 1);
                        $experience = ((50 * pow($level - 1, 3)) - (150 * pow($level - 1, 2)) + 400 * ($level - 1)) / 3;
                        $advance = (50 * pow($level - 1, 2) - 150 * ($level - 1) + 200);


//                $older = Highscores::where('name', $highscore->name)->orderBy('updated_at', 'desc')->first();
//                $advance = $older ? intval($experience - $older->experience) : 0;
//                    $advance = 0;

                        Highscores::create([
                            'rank'       => $index + 1,
                            'name'       => $highscore->name,
                            'vocation'   => $highscore->voc,
                            'experience' => $experience,
                            'level'      => $level,
                            'advance'    => $advance,
                            'world_id'   => $world->id,
                            'updated_at' => $today,
                            'type'       => $this->argument('type'),
                        ]);
                    });
                });
            }
        }
    }
}
