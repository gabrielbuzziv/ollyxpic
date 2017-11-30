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
            $spot->supplies()->attach([
                $item->id => [
                    'amount'      => $supply['amount'],
                    'description' => $supply['description']
                ]
            ]);
        }

        return $data;
    }

    public function show(HuntingSpot $spot)
    {
        $spot = HuntingSpot::with('creatures', 'supplies')->find($spot->id);

        return $this->respond($spot->toArray());
    }
}
