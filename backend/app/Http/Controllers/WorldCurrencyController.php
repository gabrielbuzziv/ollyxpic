<?php

namespace App\Http\Controllers;

use App\World;
use App\WorldCurrency;
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

    /**
     * Update currency.
     *
     * @param WorldCurrency $currency
     * @return mixed
     */
    public function update(WorldCurrency $currency)
    {
        $currency->update(request()->all());

        return $this->respond($currency->toArray());
    }

    /**
     * Destroy currency.
     *
     * @param WorldCurrency $currency
     * @return mixed
     */
    public function destroy(WorldCurrency $currency)
    {
        $currency->delete();

        return $this->respond(['removed' => true]);
    }
}
