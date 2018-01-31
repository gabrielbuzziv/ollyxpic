<?php

namespace App\Http\Controllers;

use App\Highscores;
use App\World;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HighscoresController extends ApiController
{

    /**
     * Return highscores by experience.
     *
     * @return mixed
     */
    public function experience()
    {
        $world = request('world') ? World::where('name', request('world'))->first()->id : null;

        $highscores = (new Highscores)
            ->with('world')
            ->with('weekExperience')
            ->where('type', 'experience')
            ->where(function ($query) use ($world) {
                if ($world)
                    $query->where('world_id', $world);
            })
            ->whereIn('vocation', $this->getVocation())
            ->orderBy('experience', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('name', 'asc')
            ->groupBy('name')
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
        $world = request('world') ? World::where('name', request('world'))->first()->id : null;

        $highscores = (new Highscores)
            ->with('world')
            ->where('type', request('skill'))
            ->where(function ($query) use ($world) {
                if ($world)
                    $query->where('world_id', $world);
            })
            ->orderBy('level', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('name', 'asc')
            ->groupBy('name')
            ->take(300)
            ->get();

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
