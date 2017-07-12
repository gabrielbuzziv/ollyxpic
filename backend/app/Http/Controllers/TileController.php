<?php

namespace App\Http\Controllers;

use App\Tiles;
use App\WorldObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TileController extends Controller
{

    /**
     * Get all tiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tiles = Tiles::orderBy('id', 'asc')->get();

        return $this->respond($tiles->toArray());
    }
}
