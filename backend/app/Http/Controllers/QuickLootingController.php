<?php

namespace App\Http\Controllers;

use App\Category;
use App\Creature;
use App\Item;
use Illuminate\Http\Request;

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
        $creatures = Creature::with('drops')->where('title', 'like', "%{$filter}%")->get();

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

    public function items()
    {
        if (! empty(request('creature'))) {
            $creature = Creature::with('drops')
                ->find(request('creature'));

            $items = ! empty(request('category')) && request('category') != 0
                ? $creature->drops()->whereNotNull('identifier')->where('category_id', request('category'))->get()
                : $creature->drops()->whereNotNull('identifier')->get();

            return $this->respond($items->toArray());
        }

        $items = Item::whereNotNull('identifier')
            ->where(function ($query) {
                if (! empty(request('category')) && request('category') != 0)
                    $query->where('category_id', request('category'));
            })
            ->get();

        return $this->respond($items->toArray());
    }
}
