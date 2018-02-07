<?php

namespace App\Http\Controllers;

use App\HighscoreMigration;
use App\Highscores;
use App\World;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HighscoresController extends ApiController
{

    /**
     * Return highscores by experience.
     *
     * @return mixed
     */
    public function experience()
    {
        $migration = (new HighscoreMigration)
            ->where('active', 1)
            ->where('type', 'experience')
            ->orderBy('id', 'desc')
            ->first();
        $world = request('world') ? World::where('name', request('world'))->first()->id : null;

        $highscores = (new Highscores)
            ->with('world')
            ->with('weekExperience')
            ->where('migration_id', $migration->id)
            ->whereIn('vocation', $this->getVocation())
            ->where(function ($query) use ($world) {
                if ($world)
                    $query->where('world_id', $world);
            })
            ->orderBy('experience', 'desc')
            ->take(300)
            ->get();

        return $this->respond($highscores->toArray());
    }

    /**
     * Get highscores by skill.
     *
     * @return mixed
     */
    public function skills()
    {
        $migration = (new HighscoreMigration)
            ->where('active', 1)
            ->where('type', request('skill'))
            ->orderBy('id', 'desc')
            ->first();

        $world = request('world') ? World::where('name', request('world'))->first()->id : null;

        $highscores = (new Highscores)
            ->with('world')
            ->where('migration_id', $migration->id)
            ->where(function ($query) use ($world) {
                if ($world)
                    $query->where('world_id', $world);
            });

        if (in_array(request('skill'), ['achievements', 'loyalty'])) {
            $highscores = $highscores->orderBy('experience', 'desc');
        } else {
            $highscores = $highscores->orderBy('level', 'desc');
        }

        $highscores = $highscores->take(300)->get();

        return $this->respond($highscores->toArray());
    }

    /**
     * Get the requested vocation.
     *
     * @return array|bool
     */
    private function getVocation()
    {
        switch (request('vocation')) {
            case 'knight':
                return ['Knight', 'Elite Knight'];
            case 'sorcerer':
                return ['Sorcerer', 'Master Sorcerer'];
            case 'druid':
                return ['Druid', 'Elder Druid'];
            case 'paladin':
                return ['Paladin', 'Royal Paladin'];
            default:
                return ['Knight', 'Elite Knight', 'Sorcerer', 'Master Sorcerer', 'Paladin', 'Royal Paladin', 'Druid', 'Elder Druid'];
        }
    }
}
