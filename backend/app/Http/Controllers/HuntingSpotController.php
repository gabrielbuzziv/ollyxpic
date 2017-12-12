<?php

namespace App\Http\Controllers;

use App\Http\Requests\HuntingSpotRequest;
use App\HuntingSpot;
use App\Item;
use App\Vocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HuntingSpotController extends Controller
{

    /**
     * Get all hunting spots.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sort = $this->getSort();

        $filters = json_decode(request('filters'));

        $spots = HuntingSpot::with('vocations', 'creatures')
            ->where(function ($query) use ($filters) {
                $query->where('level_min', '>=', $filters->level[0]);
                $query->where('level_max', '<=', $filters->level[1]);
                $query->where('experience', '>=', $filters->experience);
                $query->where('profit', '>=', $filters->profit);

                if ($filters->vocation) {
                    $query->whereRaw("{$filters->vocation} in (SELECT vocation_id FROM hunting_spot_vocation WHERE hunting_spot_id = hunting_spots.id)");
                }
            })
            ->orderBy($sort->value, $sort->order)
            ->paginate(request('limit'));

        return $this->respond([
            'total' => $spots->total(),
            'items' => $spots->items()
        ]);
    }

    /**
     * Store hunting spot.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['soloable'] = $data['soloable'] == 'true' ? 1 : 0;
        $data['has_task'] = $data['has_task'] == 'true' ? 1 : 0;
        $data['require_quest'] = $data['require_quest'] == 'true' ? 1 : 0;
        $data['require_premium'] = $data['require_premium'] == 'true' ? 1 : 0;
        $data['password'] = str_random(8);
        $data['active'] = 0;

        $spot = HuntingSpot::create($data);

        // Attach vocations.
        foreach ($data['vocations'] as $vocation) {
            $vocation = Vocation::where('title', $vocation)->first();
            $spot->vocations()->attach($vocation->id);
        }

        // Attach creatures.
        foreach ($data['creatures'] as $creature) {
            $spot->creatures()->attach($creature);
        }

        // Attach supplies.
        foreach ($data['supplies'] as $supply) {
            $item = explode(',', $supply['item'])[1];
            $item = Item::where('title', $item)->first();
            $spot->supplies()->attach([$item->id => ['amount' => $supply['amount']]]);
        }

        // Attach supplies.
        foreach ($data['equipments'] as $equipment) {
            $item = explode(',', $equipment['item'])[1];
            $item = Item::where('title', $item)->first();
            $spot->equipments()->attach($item->id);
        }

        return $data;
    }

    /**
     * Show hunting spot.
     *
     * @param HuntingSpot $spot
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(HuntingSpot $spot)
    {
        $spot = HuntingSpot::with('creatures.drops', 'supplies', 'equipments')->find($spot->id);

        return $this->respond($spot->toArray());
    }

    /**
     * Get supplies for hunting spot
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function equipments()
    {
        $supplies = Item::where('category_id', $this->parseCategory(request('category')))->get();

        return $this->respond($supplies->toArray());
    }

    /**
     * Get supplies for hunting spot
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function supplies()
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
            case 'Swords':
                return 44;
            case 'Axes':
                return 5;
            case 'Clubs':
                return 10;
            case 'Distance':
                return 16;
            case 'Wands':
                return 49;
            case 'Rods':
                return 39;
            case 'Shields':
                return 41;
            case 'Spellbooks':
                return 42;
            case 'Helmets':
                return 25;
            case 'Armors':
                return 3;
            case 'Legs':
                return 28;
            case 'Boots':
                return 8;
            case 'Tools':
                return 46;
        }
    }

    /**
     * Parse Sort.
     *
     * @return object
     */
    private function getSort()
    {
        $sort = explode(':', request('sort'));
        return (object) ['value' => $sort[0], 'order' => $sort[1]];
    }
}
