<?php

namespace App\Http\Controllers;

use App\WikiWorldMap;
use App\WorldMap;
use Illuminate\Http\Request;

class WorldMapController extends Controller
{

    /**
     * Get world map from database.db
     * Syncronize with mysql db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function syncronize()
    {
        $map = WikiWorldMap::get();

        return $map->each(function ($ground) {
            $data = WorldMap::firstOrNew(['z' => $ground->z]);
            $data->image = $ground->image;
            $data->save();
        });

        return $map;
    }
}
