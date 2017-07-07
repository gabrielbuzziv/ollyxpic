<?php

namespace App\Http\Controllers;

use App\Tiles;
use App\WorldObject;
use Illuminate\Http\Request;

class TileController extends Controller
{

    public function import()
    {
        $tiles = [
            44, 46, 49, 50, 55, 57, 5, 8, 59, 62, 63, 64, 65, 66, 67, 70, 74, 166, 280, 378, 383, 554, 555, 556, 557, 630, 700, 701, 702, 736, 740, 741, 742, 743, 758, 759, 761, 764, 765, 784, 802, 816, 1080, 1153, 1202, 1203,
        ];

        foreach ($tiles as $tile) {
            $object = WorldObject::find($tile);
            Tiles::create([
                'name'      => $object->title,
                'friction'  => 0,
                'object_id' => $object->id,
            ]);
        }
    }

    public function index()
    {
        $tiles = Tiles::all();

        return $this->respond($tiles->toArray());
    }

    public function updateFriction(Tiles $tile)
    {
        $data = request()->all();

        $tile->update(['friction' => $data['friction']]);

        return $this->respond($tile->toArray());
    }
}
