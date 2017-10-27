<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Items;
use Illuminate\Http\Request;

class DamageProtectionController extends Controller
{

    /**
     * Get items by category
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function itemsByCategory()
    {
        $items = Items::with('categories')
            ->whereIn('category', request('categories'))
            ->get();

        return $items;
    }

    /**
     * Sync items with categories
     *
     * @param Items $item
     * @param Categories $category
     * @return mixed
     */
    public function syncItemCategory(Items $item, Categories $category)
    {
        return $item->categories()->toggle($category);
    }
}
