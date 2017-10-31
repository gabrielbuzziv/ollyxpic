<?php

namespace App\Http\Controllers;

use App\Imbuement;
use Illuminate\Http\Request;

class ImbuementController extends ApiController
{

    /**
     * Get all imbuements
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $imbuiments = Imbuement::with('items.item')->get();

        return $this->respond($imbuiments->toArray());
    }

    /**
     * Imbuement Store
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $imbuement = Imbuement::create([
            'title'       => request('title'),
            'name'        => request('name'),
            'description' => request('description')
        ]);

        foreach (request('items') as $item) {
            $imbuementItem = $imbuement->items()->firstOrNew(['tier' => $item['tier'], 'imbuement_id' => $imbuement->id]);
            $imbuementItem->item_id = $item['item_id'];
            $imbuementItem->amount = $item['amount'];
            $imbuementItem->save();
        }

        return $this->respondCreated($imbuement->toArray());
    }

    /**
     * Imbuement Update
     *
     * @param Imbuement $imbuement
     * @return $this|Imbuement|\Illuminate\Database\Eloquent\Model]
     */
    public function update(Imbuement $imbuement)
    {
        $imbuement->update([
            'title'       => request('title'),
            'name'        => request('name'),
            'description' => request('description')
        ]);

        foreach (request('items') as $item) {
            $imbuementItem = $imbuement->items()->firstOrNew(['tier' => $item['tier'], 'imbuement_id' => $imbuement->id]);
            $imbuementItem->item_id = $item['item_id'];
            $imbuementItem->amount = $item['amount'];
            $imbuementItem->save();
        }

        return $this->respond($imbuement->toArray());
    }

    public function show(Imbuement $imbuement)
    {
        $imbuement = Imbuement::with('items')->find($imbuement->id);

        return $this->respond($imbuement->toArray());
    }
}
