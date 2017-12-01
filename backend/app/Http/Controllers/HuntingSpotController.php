<?php

namespace App\Http\Controllers;

use App\Http\Requests\HuntingSpotRequest;
use App\HuntingSpot;
use App\Item;
use App\Vocation;
use Illuminate\Http\Request;

class HuntingSpotController extends Controller
{

    public function index()
    {
        $spots = HuntingSpot::get();

        return $this->respond($spots->toArray());
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
        $spot = HuntingSpot::with('creatures.drops', 'supplies')->find($spot->id);

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
}
