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
        $creatures = Creature::where('name', 'like', "%{$query}%")->get();

        return $this->respond($creatures->toArray());
    }
}
