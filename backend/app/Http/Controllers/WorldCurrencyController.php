<?php

namespace App\Http\Controllers;

use App\World;
use Illuminate\Http\Request;

class WorldCurrencyController extends ApiController
{

    /**
     * Store currency.
     *
     * @param World $world
     * @return mixed
     */
    public function store(World $world)
    {
        $currency = $world->currencies()->create(request()->all());

        return $this->respondCreated($currency->toArray());
    }
}
