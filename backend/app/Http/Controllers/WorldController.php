<?php

namespace App\Http\Controllers;

use App\World;
use App\WorldCurrency;
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
        $sort = request('sort') ? $this->getSort() : (object) ['value' => 'name', 'order' => 'asc'];
        $filters = json_decode(request('filters'));

        $worlds = (new World)
            ->with('currencies')
            ->where(function ($query) use ($filters) {
                if (! empty($filters->type))
                    $query->where('type', $filters->type);
            })
            ->orderBy($sort->value, $sort->order)
            ->get();

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

    /**
     * World Currencies
     *
     * @param World $world
     * @return mixed
     */
    public function currencies(World $world)
    {
        $currencies = $world->currencies()->take(15)->orderBy('created_at', 'desc')->get();

        return $this->respond($currencies->toArray());
    }

    /**
     * Parse sort.
     *
     * @return object
     */
    private function getSort()
    {
        $sort = explode(':', request('sort'));

        return (object) ['value' => $sort[0], 'order' => $sort[1]];
    }
}
