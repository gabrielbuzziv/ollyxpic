<?php

namespace App\Http\Controllers;

use App\Http\Requests\HuntingSpotRequest;
use App\HuntingSpot;
use Illuminate\Http\Request;

class HuntingSpotController extends Controller
{

    public function store(HuntingSpotRequest $request)
    {
        return $request->all();

        $spot = HuntingSpot::create($request->all());
    }
}
