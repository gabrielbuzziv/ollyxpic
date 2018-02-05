<?php

namespace App\Http\Controllers;

use App\Highscores;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayersController extends ApiController
{

    /**
     * Get player information.
     *
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($name)
    {
        $name = strtolower(trim($name));
        $url = "https://api.tibiadata.com/v2/characters/{$name}.json";
        $details = json_decode(file_get_contents($url));
        $name = $details->characters->data->name;

        // Throw 404 erro if not exist.
        if (isset($details->characters->error)) {
            return $this->respondNotFound(null);
        }

        $experience = (new Highscores)
            ->experience()
            ->where('name', $name)
            ->where('active', 1)
            ->where('updated_at', '>=', Carbon::today()->subDays(30))
            ->where('updated_at', '<=', Carbon::today())
            ->orderBy('updated_at', 'asc')
            ->get();


        return $this->respond([
            'details' => (array) $details->characters,
            'experience' => $experience,
            'skills' => [
                'magic' => $this->getSkills($name, 'magic'),
                'sword' => $this->getSkills($name, 'sword'),
                'axe' => $this->getSkills($name, 'axe'),
                'club' => $this->getSkills($name, 'club'),
                'shielding' => $this->getSkills($name, 'shielding'),
                'distance' => $this->getSkills($name, 'distance'),
            ]
        ]);
    }

    /**
     * Get skills
     *
     * @param $name
     * @param $type
     */
    private function getSkills($name, $type)
    {
        return (new Highscores)
            ->$type()
            ->where('name', $name)
            ->orderBy('updated_at', 'desc')
            ->first();
    }
}
