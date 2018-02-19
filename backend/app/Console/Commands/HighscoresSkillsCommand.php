<?php

namespace App\Console\Commands;

use App\HighscoreMigration;
use App\HighscoresSkills;
use App\World;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HighscoresSkillsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ollyxpic:skills {type=magic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Highscores.
     *
     * @var
     */
    protected $highscores;

    /**
     * Date.
     *
     * @var
     */
    protected $date;

    /**
     * Migration.
     *
     * @var
     */
    protected $migration;

    /**
     * Results.
     *
     * @var
     */
    protected $results;

    /**
     * HighscoresSkillsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->highscores = [];
        $this->results = 0;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->date = Carbon::today()->subDay();
        (new HighscoreMigration())
            ->where('type', $this->argument('type'))
            ->where('migration_date', $this->date)
            ->delete();

        $this->migration = HighscoreMigration::create([
            'type'           => $this->argument('type'),
            'results'        => 0,
            'active'         => 0,
            'migration_date' => $this->date
        ]);

        $vocations = ['knight', 'sorcerer', 'paladin', 'druid'];

        foreach ($vocations as $vocation) {
            $worlds = World::orderBy('name', 'asc')->get();
            $worlds->each(function ($world) use ($vocation) {
                $start = microtime(true);

                $highscores = file_get_contents("https://api.tibiadata.com/v2/highscores/{$world->name}/{$this->argument('type')}/{$vocation}.json");
                $highscores = json_decode($highscores);
                $highscores = $highscores->highscores->data;

                array_walk($highscores, function ($highscore) use ($world, $start) {
                    $skill = in_array($this->argument('type'), ['loyalty', 'achievements'])
                        ? $highscore->points
                        : $highscore->level;

                    HighscoresSkills::create([
                        'rank'         => $highscore->rank,
                        'name'         => $highscore->name,
                        'vocation'     => $highscore->voc,
                        'skill'        => $skill,
                        'world_id'     => $world->id,
                        'updated_at'   => $this->date,
                        'migration_id' => $this->migration->id,
                        'active'       => 0,
                        'type'         => $this->argument('type'),
                    ]);

                    $this->results = $this->results + 1;
                    $percentage = ($this->results * 100) / 76800;
                    $time = microtime(true) - $start;
                    $remains = number_format((($time * 256) - ($percentage * ($time * 256)) / 100) / 60, 2);
                    $minutes = floor($remains);
                    $seconds = round(60 * ($remains - $minutes));
                    $remains = "{$minutes}:$seconds";

                    if (is_int($percentage) == 1) {
                        system('clear');
                        $this->info("{$percentage}% completed of 100%.");
                        $this->info("Time remains: {$remains} minutes.");
                    }
                });
            });
        }

        $this->migration->results = $this->results;
        $this->migration->active = 1;
        $this->migration->save();
        (new HighscoresSkills)
            ->where('active', 0)
            ->where('updated_at', $this->date)
            ->where('type', $this->argument('type'))
            ->update(['active' => 1]);


        (new HighscoreMigration())
            ->where('type', $this->argument('type'))
            ->where('migration_date', '<', $this->date)
            ->delete();
    }
}
