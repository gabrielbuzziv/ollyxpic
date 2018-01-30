<?php

namespace App\Http\Controllers;

use App\Highscores;
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
        $highscores = (new Highscores)
            ->with('world')
            ->with('weekExperience')
            ->where('type', 'experience')
            ->whereIn('vocation', $this->getVocation())
            ->orderBy('experience', 'desc')
            ->orderBy('updated_at', 'desc')
            ->groupBy('name')
            ->take(300)
            ->get();

        return $this->respond($highscores->toArray());
    }

    /**
     * Get Player advances.
     *
     * @param $name
     * @param string $type
     * @return mixed
     */
    public function playerAdvances($name, $type = 'experience')
    {
        $advances = Highscores::where('name', $name)
            ->where('type', $type)
            ->take(7)
            ->orderBy('updated_at', 'asc')
            ->get();

        $advances = array_map(function ($advance) {
            $advance = (object) $advance;

            return [
                'experience' => $advance->experience,
                'level'      => $advance->level,
                'updated_at' => $advance->updated_at,
            ];
        }, $advances->toArray());

        return $this->respond($advances);
    }

    /**
     * Get player details.
     *
     * @param $name
     * @return mixed
     */
    public function player($name)
    {
        $details = file_get_contents("https://api.tibiadata.com/v2/characters/{$name}.json");
        $details = (array) json_decode($details)->characters;

        return $this->respond($details);
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
