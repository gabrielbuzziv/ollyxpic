<?php

namespace App\Http\Controllers;

use App\Highscores;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayersController extends Controller
{

    /**
     * Get player information.
     *
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($name)
    {
        $url = "https://api.tibiadata.com/v2/characters/{$name}.json";
        $details = json_decode(file_get_contents($url));

        $experience = (new Highscores())
            ->experience()
            ->where('name', $name)
            ->where('updated_at', '>=', Carbon::today()->subMonth())
            ->where('updated_at', '<=', Carbon::today())
            ->orderBy('updated_at', 'asc')
            ->get();

        return $this->respond([
            'details' => (array) $details->characters,
            'experience' => $experience
        ]);
    }
}
