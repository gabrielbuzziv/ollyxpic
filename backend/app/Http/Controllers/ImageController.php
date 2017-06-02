<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function load(Items $item)
    {
        return response()->make($item->image, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Content-Disposition', 'inline; filename="test.gif"');
    }
}
