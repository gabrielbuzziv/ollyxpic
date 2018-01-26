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
        $lastUpdate = (new Highscores)
            ->select('updated_at')
            ->orderBy('updated_at','desc')
            ->take(1)
            ->first();
        $lastUpdate = $lastUpdate ? $lastUpdate->updated_at : Carbon::today();

        $highscores = (new Highscores)
            ->with('world')
            ->with('weekExperience')
            ->experience()
            ->where('updated_at', $lastUpdate)
            ->orderBy('experience', 'desc')
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
}
