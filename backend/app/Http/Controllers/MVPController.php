<?php

namespace App\Http\Controllers;

use App\MVP;
use App\MVPPlayer;
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
     * Warzone bosses and stats.
     *
     * @var array
     */
    protected $creature = [
        1 => [
            'name'      => 'Deathstrike',
            'hitpoints' => 200000,
        ],
        2 => [
            'name'      => 'Gnomevil',
            'hitpoints' => 250000,
        ],
        3 => [
            'name'      => 'Abyssador',
            'hitpoints' => 300000,
        ],
    ];

    /**
     * Calculate MVP
     *
     * @return mixed
     */
    public function calculate()
    {
        $this->validate(request(), [
            'warzone' => 'required',
            'log'     => 'required',
        ]);

        $data = request()->all();

        $this->readLog();
        $this->calculateParticipation();

        if (! $this->validateMVPS()) {
            abort(400, "The log is not valid, did you selected the right boss? Check if the log have data about the {$this->creature[$data['warzone']]['name']}");
        }


        $mvp = MVP::create([
            'title' => "Warzone {$data['warzone']}",
            'log'   => $data['log'],
            'boss'  => $this->creature[$data['warzone']]['name']
        ]);

        foreach ($this->players as $name => $player) {
            $mvp->players()->create([
                'player'        => $name,
                'experience'    => isset($player['experience']) ? $player['experience'] : 0,
                'besthit'       => isset($player['besthit']) ? $player['besthit'] : 0,
                'damage'        => isset($player['damage']) ? $player['damage'] : 0,
                'participation' => isset($player['participation']) ? $player['participation'] : 0,
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
            $creature = $this->creature[request()->input('warzone')];

            // Deal Damage
            if (strpos($log, "{$creature['name']} loses") !== false) {
                if (strpos($log, "due to your attack") !== false) {
                    $player = 'You';
                } else {
                    $player = explode('attack by', $log);
                    $player = trim(str_replace('.', '', $player[1]));
                }

                $damage = (int) filter_var($log, FILTER_SANITIZE_NUMBER_INT);

                if (isset($this->players[$player]['damage'])) {
                    $this->players[$player]['damage'] = $this->players[$player]['damage'] + $damage;
                } else {
                    $this->players[$player]['damage'] = $damage;
                }

                if (isset($this->players[$player]['besthit'])) {
                    if ($this->players[$player]['besthit'] < $damage) {
                        $this->players[$player]['besthit'] = $damage;
                    }
                } else {
                    $this->players[$player]['besthit'] = $damage;
                }

                return $player;
            } else if (strpos($log, "gained") !== false) {
                $player = explode('gained', $log);
                $player = trim(str_replace('.', '', $player[0]));

                $experience = (int) filter_var($log, FILTER_SANITIZE_NUMBER_INT);

                $this->players[$player]['experience'] = $experience;
            }
        }, $log);


    }

    /**
     * Calculate participation by damage.
     */
    private function calculateParticipation()
    {
        if (count($this->players) > 0) {
            array_walk($this->players, function ($player, $key) {
                $creature = $this->creature[request()->input('warzone')];

                if (isset($this->players[$key]['damage'])) {
                    $this->players[$key]['participation'] = ($this->players[$key]['damage'] * 100) / $creature['hitpoints'];
                }
            });
        }
    }

    /**
     * Validate
     *
     * @return bool
     */
    private function validateMVPS()
    {
        if (count($this->players) > 0) {
            return (bool) array_reduce($this->players, function ($carry, $player) {
                if (isset($player['damage'])) {
                    return $carry + $player['damage'];
                }

                return $carry;
            });
        }

        return 0;
    }
}
