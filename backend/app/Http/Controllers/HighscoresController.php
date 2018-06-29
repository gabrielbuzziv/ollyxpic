<?php

namespace App\Http\Controllers;

use App\HighscoreMigration;
use App\Highscores;
use App\HighscoresSkills;
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
        $worlds = null;

        switch (request('type')) {
            case 'retro':
                $worlds = World::where('type', 'Retro Open PvP')->pluck('id')->all();
                break;
            case 'hardcore':
                $worlds = World::where('type', 'Hardcore PvP')->pluck('id')->all();
                break;
            case 'retro-hardcore':
                $worlds = World::where('type', 'Retro Hardcore PvP')->pluck('id')->all();
                break;
            case 'npvp':
                $worlds = World::where('type', 'Optional PvP')->pluck('id')->all();
                break;
            case 'pvp';
                $worlds = World::where('type', 'Open PvP')->pluck('id')->all();
                break;
            default:
                $worlds = null;
        }

        $limit = request('limit') ?: 300;
        $world = request('world') ? World::where('name', request('world'))->first()->id : null;
        $migration = (new HighscoreMigration)
            ->where('type', 'experience')
            ->where('active', 1)
            ->orderBy('migration_date', 'desc')
            ->first();

        $highscores = (new Highscores)
            ->with('world')
            ->where('migration_id', $migration->id)
            ->whereIn('vocation', $this->getVocation())
            ->when($world, function ($query) use ($world) {
                $query->where('world_id', $world);
            })
            ->when($worlds, function($query) use ($worlds) {
                $query->whereIn('world_id', $worlds);
            })
            ->orderBy('experience', 'desc')
            ->take($limit)
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
        $limit = request('limit') ?: 300;
        $world = request('world') ? World::where('name', request('world'))->first()->id : null;
        $migration = (new HighscoreMigration)
            ->where('type', request('skill'))
            ->where('active', 1)
            ->orderBy('migration_date', 'desc')
            ->first();

        $highscores = (new HighscoresSkills())
            ->with('world')
            ->where('migration_id', $migration->id)
            ->where('type', request('skill'))
            ->when($world, function ($query) use ($world) {
                $query->where('world_id', $world);
            })
            ->orderBy('skill', 'desc')
            ->take($limit)
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
