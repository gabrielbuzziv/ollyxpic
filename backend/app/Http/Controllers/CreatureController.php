<?php

namespace App\Http\Controllers;

use App\Creature;
use App\Item;
use App\WikiCreatures;
use Illuminate\Http\Request;

class CreatureController extends ApiController
{

    /**
     * Get creatures from database.db
     * Syncronize with mysql db.
     *
     * @return $this
     */
    public function syncronize()
    {
        $creatures = WikiCreatures::with('drops')->get();

        return $creatures->each(function ($creature) {
            $data = Creature::firstOrNew(['title' => $creature->title]);
            $data->name = $creature->name;
            $data->health = $creature->health;
            $data->experience = $creature->experience;
            $data->maxdamage = $creature->maxdamage;
            $data->summon = $creature->summon;
            $data->illusionable = $creature->illusionable;
            $data->pushable = $creature->pushable;
            $data->pushes = $creature->pushes;
            $data->physical = $creature->physical;
            $data->holy = $creature->holy;
            $data->death = $creature->death;
            $data->fire = $creature->fire;
            $data->energy = $creature->energy;
            $data->ice = $creature->ice;
            $data->earth = $creature->earth;
            $data->drown = $creature->drown;
            $data->lifedrain = $creature->lifedrain;
            $data->paralysable = $creature->paralysable;
            $data->senseinvis = $creature->senseinvis;
            $data->image = $creature->image;
            $data->abilities = $creature->abilities;
            $data->speed = $creature->speed;
            $data->armor = $creature->armor;
            $data->boss = $creature->boss;
            $data->save();

            $drops = $creature->drops;
            $creatureDrops = [];

            foreach ($drops as $drop) {
                $item = Item::where('title', $drop->title)->first();

                if ($item) {
                    $creatureDrops[$item->id] = [
                        'percentage' => isset($drop->pivot->percentage) ? $drop->pivot->percentage : null,
                        'min'        => isset($drop->pivot->min) ? $drop->pivot->min: null,
                        'max'        => isset($drop->pivot->max) ? $drop->pivot->max: null,
                    ];
                }
            }

            $data->drops()->sync($creatureDrops);

            return $creature;
        });
    }

    /**
     * Get all creatures.
     */
    public function index()
    {
        $filter = request('query');
        $creatures = Creature::where('title', 'like', "%{$filter}%")->get();

        return $this->respond($creatures->toArray());
    }

    /**
     * Show creature.
     *
     * @param Creature $creature
     * @return mixed
     */
    public function show(Creature $creature)
    {
        return $this->respond($creature->toArray());
    }

    /**
     * Get creature by name.
     *
     * @return mixed
     */
    public function search()
    {
        $name = request('creature');
        $creature = (new Creature)
            ->with('drops')
            ->where('name', 'like', "{$name}%")
            ->first();

        return $this->respond($creature->toArray());
    }

    /**
     * Get multiple creatures.
     *
     * @return mixed
     */
    public function multiple()
    {
        $creatures = Creature::whereIn('name', request('creatures'))->get();

        return $this->respond($creatures->toArray());
    }
}
