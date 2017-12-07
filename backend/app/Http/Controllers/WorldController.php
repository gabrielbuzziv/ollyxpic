<?php

namespace App\Http\Controllers;

use App\World;
use Illuminate\Http\Request;

class WorldController extends ApiController
{
    /**
     * Get all worlds
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $worlds = World::with('currencies')->orderBy('name', 'asc')->get();

        return $this->respond($worlds->toArray());
    }

    /**
     * World Store
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $world = World::create(request()->all());

        return $this->respondCreated($world->toArray());
    }

    /**
     * World Update
     *
     * @param World $world
     * @return mixed
     */
    public function update(World $world)
    {
        $world->update(request()->all());

        return $this->respond($world->toArray());
    }

    /**
     * @param World $world
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function show(World $world)
    {
        $world = World::with('currencies')->find($world->id);

        return $this->respond($world->toArray());
    }
}
