<?php

namespace App\Http\Controllers;

use App\Highscores;
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
        $highscores = Highscores::experience()->get();

        return $this->respond($highscores->toArray());
    }
}
