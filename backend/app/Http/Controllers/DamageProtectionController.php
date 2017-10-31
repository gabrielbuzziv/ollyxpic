<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
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
        $items = Item::with('categories')
            ->whereIn('category', request('categories'))
            ->get();

        return $items;
    }

    /**
     * Sync items with categories
     *
     * @param Item $item
     * @param Category $category
     * @return mixed
     */
    public function syncItemCategory(Item $item, Category $category)
    {
        return $item->categories()->toggle($category);
    }
}
