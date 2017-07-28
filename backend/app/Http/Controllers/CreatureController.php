<?php

namespace App\Http\Controllers;

use App\Creature;
use Illuminate\Http\Request;

class CreatureController extends Controller
{

    /**
     * Search creatures.
     *
     * @return mixed
     */
    public function search()
    {
        $query = request()->input('query');
        $creatures = Creature::where('name', 'like', "%{$query}%")
            ->where('health', '>', 200)->get();

        return $this->respond($creatures->toArray());
    }

    /**
     * Find creature.
     *
     * @param Creature $creature
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Creature $creature)
    {
        return $this->respond($creature->toArray());
    }
}
