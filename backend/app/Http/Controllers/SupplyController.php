<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class SupplyController extends Controller
{

    public function index()
    {
        $supplies = Item::where('category_id', $this->parseCategory(request('category')))->get();

        return $this->respond($supplies->toArray());
    }

    /**
     * Convert the category string value in the category id.
     *
     * @param $category
     * @return int
     */
    private function parseCategory($category)
    {
        switch ($category) {
            case 'Potions':
                return 30;
            case 'Rings':
                return 38;
            case 'Amulets':
                return 2;
            case 'Ammunitions':
                return 1;
        }
    }
}
