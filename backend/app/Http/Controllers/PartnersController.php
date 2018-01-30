<?php

namespace App\Http\Controllers;

use App\Partners;
use Illuminate\Http\Request;

class PartnersController extends ApiController
{

    /**
     * Get all partners.
     *
     * @return mixed
     */
    public function index()
    {
        $partners = Partners::all();

        return $this->respond($partners->toArray());
    }

    /**
     * Store partner in database.
     *
     * @return mixed
     */
    public function store()
    {
        $partner = Partners::create(request()->all());

        return $this->respondCreated($partner->toArray());
    }

    /**
     * Update partner in database.
     *
     * @param Partners $partner
     * @return mixed
     */
    public function update(Partners $partner)
    {
        $partner->update(request()->all());

        return $this->respond($partner->toArray());
    }

    /**
     * Delete partner.
     *
     * @param Partners $partner
     * @return mixed
     */
    public function destroy(Partners $partner)
    {
        $partner->delete();

        return $this->respond(['deleted' => true]);
    }

    /**
     * Get and return partner object.
     *
     * @param Partners $partner
     * @return mixed
     */
    public function show(Partners $partner)
    {
        return $this->respond($partner->toArray());
    }
}
