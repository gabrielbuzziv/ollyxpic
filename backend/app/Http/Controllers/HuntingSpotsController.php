<?php

namespace App\Http\Controllers;

use App\Huntingspot;
use App\Creature;
use App\Creaturedrops;
use Illuminate\Http\Request;

class HuntingSpotsController extends Controller
{
    /**
     * Get all huntingspots.
     */
    public function index()
    {

        $filter = request('query');
        $huntingspots = Huntingspot::with('creatures', 'itemdrops')->where('title', 'like', "%{$filter}%")->get();

        return $this->respond($huntingspots->toArray());
    }

    /**
     * Show huntingspots.
     *
     * @param Huntingspot $huntingspot
     * @return mixed
     */
    public function show(Huntingspot $huntingspot)
    {
        $huntingspot = Huntingspot::with('creatures', 'itemdrops')->find($huntingspot->id);

        return $this->respond($huntingspot->toArray());
    }
}
