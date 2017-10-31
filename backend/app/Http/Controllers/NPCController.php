<?php

namespace App\Http\Controllers;

use App\NPC;
use App\WikiNPC;
use Illuminate\Http\Request;

class NPCController extends Controller
{

    /**
     * Get npcs from database.db
     * If the npc is new create a new one, if not just update data.
     *
     * @return $this
     */
    public function syncronize()
    {
        $npcs = WikiNPC::get();

        return $npcs->each(function ($npc) {
            $data = NPC::firstOrNew(['name' => $npc->name]);
            $data->title = $npc->title;
            $data->city = $npc->city;
            $data->job = $npc->job;
            $data->x = $npc->x;
            $data->y = $npc->y;
            $data->z = $npc->z;
            $data->image = $npc->image;
            $data->save();

            return $npc;
        });
    }
}
