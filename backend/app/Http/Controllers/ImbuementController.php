<?php

namespace App\Http\Controllers;

use App\Imbuements;
use Illuminate\Http\Request;

class ImbuementController extends Controller
{

    public function index()
    {
        $imbuiments = Imbuements::all();

        return $this->respond($imbuiments->toArray());
    }
}
