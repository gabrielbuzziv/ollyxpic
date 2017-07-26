<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseController extends Controller
{

    public function update()
    {
        $tibiawiki = storage_path('app/tibiawiki_pages_current.xml');
        $file = simplexml_load_string($tibiawiki);

        return $file;
    }
}
