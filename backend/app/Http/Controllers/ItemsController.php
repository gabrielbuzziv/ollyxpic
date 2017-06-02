<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

    /**
     * Return items by request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Items::paginate(25);

        return $this->respond($items->toArray());
    }
}
