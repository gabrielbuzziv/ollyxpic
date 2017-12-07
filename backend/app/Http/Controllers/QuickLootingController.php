<?php

namespace App\Http\Controllers;

use App\Category;
use App\Creature;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuickLootingController extends Controller
{

    /**
     * Fetch creatures with drops.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creatures()
    {
        $filter = request('query');
        $creatures = Creature::where('title', 'like', "%{$filter}%")->get();

        return $this->respond($creatures->toArray());
    }

    /**
     * Get categories to blacklist
     *
     * @return mixed
     */
    public function categories()
    {
        $categoriesEnable = Item::select('category_id')
            ->whereNotNull('identifier')
            ->groupBy('category_id')
            ->pluck('category_id');

        $categories = Category::whereIn('id', $categoriesEnable)
            ->orderBy('title', 'asc')
            ->get();

        return $this->respond($categories->toArray());
    }

    /**
     * Get items by filter and show.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function items()
    {
        if ( ! empty(request('creatures'))) {
            $items = DB::table('creature_item')
                ->whereIn('creature_id', request('creatures'))
                ->groupBy('item_id')
                ->pluck('item_id');

            $items = ! empty(request('category')) && request('category') != 0
                ? Item::whereNotNull('identifier')->whereIn('id', $items)->where('category_id', request('category'))->orderBy('title', 'asc')->get()
                : Item::whereNotNull('identifier')->whereIn('id', $items)->orderBy('title', 'asc')->get();

            return $this->respond($items->toArray());
        }

        $items = Item::whereNotNull('identifier')
            ->where(function ($query) {
                if ( ! empty(request('category')) && request('category') != 0)
                    $query->where('category_id', request('category'));
            })
            ->orderBy('title', 'asc')
            ->get();

        return $this->respond($items->toArray());
    }
}
