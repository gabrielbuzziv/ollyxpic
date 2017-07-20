<?php

namespace App\Http\Controllers;

use App\MVP;
use Illuminate\Http\Request;

class MVPController extends Controller
{

    // Experience (Dom Pedrinn gained 11 experience points.)
    // Damage (A cave rat loses 30 hitpoints due to an attack by Dom Pedrinn.)

    /**
     * Players
     *
     * @var
     */
    protected $players;

    /**
     * Calculate MVP
     *
     * @return mixed
     */
    public function calculate()
    {
        $this->readLog();

        $mvp = MVP::create(request()->all());

        foreach ($this->players as $name => $player) {
            $mvp->players()->create([
                'player'     => $name,
                'experience' => isset($player['experience']) ? $player['experience'] : 0,
                'besthit'    => isset($player['besthit']) ? $player['besthit'] : 0,
            ]);
        }

        return $this->respond($mvp->toArray());
    }

    /**
     * Show mvps
     *
     * @param MVP $mvp
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(MVP $mvp)
    {
        return $this->respond($mvp->toArray());
    }

    /**
     * Read Log
     * 
     * @return array
     */
    private function readLog()
    {
        $log = array_filter(explode(PHP_EOL, request()->input('log')), function ($log) {
            return strpos($log, 'experience points') || strpos($log, 'hitpoints due to');
        });

        return array_map(function ($log) {
            $log = substr($log, 6);

            if (strpos($log, 'hitpoints due to')) {
                $player = trim(str_replace(['attack by', '.'], '', substr($log, strpos($log, 'attack by'))));
                $damage = (int) filter_var($log, FILTER_SANITIZE_NUMBER_INT);

                if (isset($this->players[$player]['besthit'])) {
                    if ($this->players[$player]['besthit'] < $damage) {
                        $this->players[$player]['besthit'] = $damage;
                    }
                } else {
                    $this->players[$player]['besthit'] = $damage;
                }
            } else {
                $player = trim(substr($log, 0, strpos($log, 'gained')));
                $experience = (int) filter_var($log, FILTER_SANITIZE_NUMBER_INT);

                $this->players[$player]['experience'] = $experience;
            }
        }, $log);


    }
}
