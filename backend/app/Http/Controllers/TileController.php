<?php

namespace App\Http\Controllers;

use App\Tile;
use App\WorldObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TileController extends Controller
{

    /**
     * Insert tiles.
     */
    public function syncronize()
    {
        $tiles = [
            [2949, 'Earth', 110, 103],
            [2950, 'Chalk', 160, 104],
            [2951, 'Grass', 140, 106],
            [2952, 'Grass', 140, 109],
            [2953, 'Mud', 110, 194],
            [2954, 'Sand', 160, 231],
            [2955, 'Earth', 110, 280],
            [2956, 'Dirt (Light)', 130, 352],
            [2957, 'Dirt (Medium)', 140, 353],
            [2958, 'Dirt (Heavy)', 200, 355],
            [2959, 'Loose Board', 100, 408],
            [2960, 'White Marble Floor', 100, 409],
            [2961, 'Black Marble Floor', 100, 410],
            [2962, 'Sandstone Floor', 100, 415],
            [2963, 'Stone Floor', 100, 416],
            [2964, 'Tiled Floor', 100, 417],
            [2965, 'Sandstone', 100, 422],
            [2966, 'Tiled Floor (Yellow)', 100, 423],
            [2967, 'Sandstone Floor', 70, 425],
            [2968, 'Stone Tile', 100, 429],
            [2969, 'Stone Floor', 100, 436],
            [2970, 'Wooden Floor (Yellow)', 100, 454],
            [2971, 'White Stone', 100, 463],
            [2972, 'Tile', 100, 464],
            [2973, 'Wooden Floot (Docks)', 100, 486],
            [2974, 'Banuta Floor', 100, 499],
            [2975, 'Strange Carving', 100, 596],
            [2976, 'Snow', 160, 799],
            [2977, 'Ice', 100, 800],
            [2978, 'Cobblestone Pavement', 100, 870],
            [2979, 'Ankrahmun Floor', 100, 923],
            [2980, 'Dried Grass', 150, 970],
            [2981, 'Dry Earth', 120, 982],
            [2982, 'Jungle Grass', 150, 1019],
            [2983, 'Earth', 140, 1020],
            [2984, 'Drawbridge', 90, 1771],
            [2985, 'Port Hope Bridge', 200, 2253],
            [2986, 'Rock', 120, 4394],
            [2987, 'Grass', 150, 4515],
            [2988, 'Gravel', 150, 4555],
            [2989, 'Ocean Floor', 250, 5406],
            [2990, 'Swamp', 200, 6352],
            [2991, 'Ground', 100, 6388],
            [2992, 'Farmine Mountains Floor', 150, 7062],
            [2993, 'Coblestone', 100, 7593],
            [2994, 'Iron Floor', 100, 8276],
            [2995, 'Floor', 100, 9481],
            [2996, 'Floor', 110, 9524],
            [2997, 'Floor', 100, 9575],
            [2998, 'Floor', 110, 9700],
            [2999, 'Floor', 120, 10160],
            [3000, 'Floor', 200, 10237],
            [3001, 'Bog Water', 200, 10494],
            [3002, 'Hellgate Floor', 120, 11925],
            [3003, 'Floor', 150, 12090],
            [3004, 'Yielothax Floor', 120, 12379],
            [3005, 'Floor', 125, 12851],
            [3006, 'Mud', 140, 13184],
            [3007, 'Deepling Floor', 100, 13430],
            [3008, 'Deepling Sand', 150, 13533],
            [3009, 'Crystal Floor', 100, 14770],
            [3010, 'Crystal Glass Floor', 100, 15469],
            [3011, 'Corym Floor', 110, 16280],
            [3012, 'Venore Corym Bridges', 110, 16484],
            [3013, 'Floor', 110, 19271],
            [3014, 'Floor', 100, 19558],
            [3015, 'Floor', 150, 19825],
            [3016, 'Oramond Ground', 150, 20374],
            [3017, 'Oramond', 150, 20535],
            [3018, 'Floor', 140, 21484],
            [3019, 'Krailos Surface Floor', 150, 22355],
            [3020, 'Otherworld Floor', 150, 23048],
            [3021, 'Floor', 150, 23098],
            [3022, 'Floor', 150, 23897],
            [3023, 'Catacomb Floor', 100, 20712],
            [3024, 'Banuta Floor', 100, 500],
            [3025, 'Banuta Floor', 100, 516],
            [3026, 'Banuta Floor', 100, 524],
            [3028, 'Roshamuul', 110, 19550],
            [3029, 'Prision', 100, 17544],
        ];

        foreach ($tiles as $tile) {
            $dataTile = Tile::firstOrNew(['id' => (int) $tile[0]]);
            $dataTile->name = $tile[1];
            $dataTile->friction = $tile[2];
            $dataTile->object_id = $tile[3];
            $dataTile->save();
        }

        return $tiles;
    }

    /**
     * Get all tiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tiles = Tile::orderBy('id', 'asc')->get();

        return $this->respond($tiles->toArray());
    }
}
