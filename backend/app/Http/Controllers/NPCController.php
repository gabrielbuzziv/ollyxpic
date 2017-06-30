<?php

namespace App\Http\Controllers;

use App\NPC;
use Illuminate\Http\Request;

class NPCController extends Controller
{

    public function index()
    {
        $npcs = NPC::all();

        return $this->respond($npcs->toArray());
    }
}
