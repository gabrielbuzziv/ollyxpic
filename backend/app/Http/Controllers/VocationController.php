<?php

namespace App\Http\Controllers;

use App\Vocation;
use Illuminate\Http\Request;

class VocationController extends ApiController
{

    /**
     * Get all vocations.
     *
     * @return mixed
     */
    public function index()
    {
        $vocations = Vocation::get();

        return $this->respond($vocations->toArray());
    }
}
