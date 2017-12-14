<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\HuntingSpotRequest;
use App\HuntingSpot;
use App\Item;
use App\Vocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $spots = (new HuntingSpot)
            ->with('vocations', 'creatures')
            // Level range condition.
            ->where(function ($query) use ($filters) {
                $query->whereBetween('level_min', $filters->level);
                $query->orWhere('level_max', '>=', $filters->level[1]);
                $query->orWhere(function ($query) use ($filters) {
                    $query->whereBetween('level_max', $filters->level);
                });
            })
            // Experience condition
            ->where('experience', '>=', $filters->experience)
            // Options conditions
            ->where(function ($query) use ($filters) {
                if ($filters->team === true)
                    $query->where('soloable', 0);

                if ($filters->task === true)
                    $query->where('has_task', 1);

                if ($filters->quest === true)
                    $query->where('require_quest', 0);

                if ($filters->premium === true)
                    $query->where('require_premium', 1);
            })
            // Vocation Conditions
            ->where(function ($query) use ($filters) {
                if ($vocations = $this->parseVocations($filters->vocations)) {
                    foreach ($vocations as $vocation) {
                        $query->whereRaw("{$vocation} in (SELECT vocation_id FROM hunting_spot_vocation WHERE hunting_spot_id = hunting_spots.id)");
                    }
                }
            })
            ->where('active', 1)
            ->orderBy($sort->value, $sort->order)
            ->paginate(18);

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
        $data['active'] = 1;

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
            $spot->supplies()->attach([$supply['item'] => ['amount' => $supply['amount']]]);
        }

        // Attach equipments.
        if (isset($data['equipments']) && count($data['equipments']) > 0) {
            foreach ($data['equipments'] as $equipment) {
                $spot->equipments()->attach($equipment['item']);
            }
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
        $spot = HuntingSpot::with('creatures.drops', 'supplies', 'equipments', 'vocations')
            ->where(function ($query) {
                if ( ! request()->header('Authorization') || ! JWTAuth::parseToken()->authenticate())
                    $query->where('active', 1);
            })
            ->findOrFail($spot->id);

        return $this->respond($spot->toArray());
    }

    /**
     * Get categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = Category::with('items')
            ->where(function ($query) {
                if (request('categories'))
                    $query->whereIn('id', request('categories'));
            })
            ->get();

        return $this->respond($categories->toArray());
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
        $supplies = Item::where('category_id', $this->parseCategory(request('category')))
            ->where('supply', 1)
            ->orderBy('title', 'asc')
            ->get();

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
            case 'Runes':
                return 4;
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

    /**
     * Get filtered vocations.
     *
     * @param $vocations
     * @return array
     */
    private function parseVocations($vocations)
    {
        $vocations = (array) $vocations;
        $vocationId = ['knight' => 1, 'druid' => 2, 'sorcerer' => 3, 'paladin' => 4];
        $list = [];
        foreach ($vocations as $key => $vocation) {
            if ($vocation) {
                $list[] = $vocationId[$key];
            }
        }
        return $list;
    }
}
