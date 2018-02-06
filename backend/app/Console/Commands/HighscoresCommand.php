<?php

namespace App\Console\Commands;

use App\HighscoreMigration;
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
     * Highscores constructor.
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
                $highscores = file_get_contents("https://api.tibiadata.com/v2/highscores/{$world->name}/{$this->argument('type')}/{$vocation}.json");
                $highscores = json_decode($highscores);
                $highscores = $highscores->highscores->data;

                array_walk($highscores, function ($highscore) use ($world) {
                    Highscores::create([
                        'rank'         => $highscore->rank,
                        'name'         => $highscore->name,
                        'vocation'     => $highscore->voc,
                        'experience'   => $this->argument('type') == 'experience' ? $highscore->points : 0,
                        'level'        => $highscore->level,
                        'world_id'     => $world->id,
                        'updated_at'   => $this->date,
                        'migration_id' => $this->migration->id,
                        'active'       => 0,
                        'type'         => $this->argument('type'),
                    ]);

                    $this->results = $this->results + 1;
                });
            });
        }

        $this->migration->update(['results' => $this->results, 'active' => 1]);
        (new Highscores)
            ->where('active', 0)
            ->where('updated_at', $this->date)
            ->where('type', 'experience')
            ->update(['active' => 1]);
    }
}
