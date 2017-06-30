<?php

namespace App\Http\Controllers;

use App\Items;
use App\Waste;
use App\WasteItems;
use Illuminate\Http\Request;

class WasteController extends Controller
{

    /**
     * Calculate the waste and save in database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate()
    {
        $potions = $this->prepare('potions');
        $ammunitions = $this->prepare('ammunitions');
        $runes = $this->prepare('runes');
        $amulets = $this->prepare('amulets');
        $rings = $this->prepare('rings');

        $supplies = [
            'potions'     => $potions,
            'ammunitions' => $ammunitions,
            'runes'       => $runes,
            'amulets'     => $amulets,
            'rings'       => $rings,
        ];

        $waste = Waste::create([
            'potions'     => $this->sum($potions),
            'ammunitions' => $this->sum($ammunitions),
            'runes'       => $this->sum($runes),
            'amulets'     => $this->sum($amulets),
            'rings'       => $this->sum($rings),
            'total'       => $this->sum($potions) + $this->sum($ammunitions) + $this->sum($runes) + $this->sum($amulets) + $this->sum($rings),
        ]);

        foreach ($supplies as $type => $items) {
            foreach ($items as $item) {
                WasteItems::create([
                    'waste_id' => $waste->id,
                    'item_id'  => $item['id'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                    'type'     => $type,
                ]);
            }
        }

        return $this->respond($waste->toArray());
    }

    /**
     * Find the waste by id and return data.
     *
     * @param Waste $waste
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Waste $waste)
    {
        return $this->respond($waste->toArray());
    }

    /**
     * Prepare the items.
     *
     * @param $type
     * @return array
     */
    private function prepare($type)
    {
        $supplies = $this->filter($type);

        return array_map(function ($supply) {
            $price = empty($supply['price']) ? Items::find($supply['id'])->vendor_value : $supply['price'];

            return [
                'id'       => $supply['id'],
                'quantity' => $supply['quantity'],
                'price'    => $price,
            ];
        }, $supplies);
    }

    /**
     * Filter the supply to only return not empty values.
     *
     * @param $type
     * @return array
     */
    private function filter($type)
    {
        if ($supplies = request()->input($type)) {
            return array_filter($supplies, function ($supply) {
                return isset($supply['id']);
            });
        }

        return [];
    }

    /**
     * Sum the total in gold coins of supplies.
     *
     * @param $items
     * @return mixed
     */
    private function sum($items)
    {
        return array_reduce($items, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        });
    }
}
