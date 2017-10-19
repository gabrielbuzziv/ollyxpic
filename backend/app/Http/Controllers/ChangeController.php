<?php

namespace App\Http\Controllers;

use App\Change;
use App\Http\Requests\ChangeRequest;
use Illuminate\Http\Request;

class ChangeController extends ApiController
{
    /**
     * List all news.
     *
     * @return mixed
     */
    public function index()
    {
        $changes = Change::with('author')->latest()->take(50)->get();

        return $this->respond($changes->toArray());
    }

    /**
     * Store in database.
     *
     * @param ChangeRequest $request
     * @return array
     */
    public function store(ChangeRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $change = Change::create($data);

        return $this->respondCreated($change->toArray());
    }

    /**
     * Store in database.
     *
     * @param Change $change
     * @param ChangeRequest $request
     * @return array
     */
    public function update(Change $change, ChangeRequest $request)
    {
        $data = $request->all();

        $change->update($data);

        return $this->respond($change->toArray());
    }

    /**
     * Destroy post.
     *
     * @param Change $change
     * @return mixed
     */
    public function destroy(Change $change)
    {
        $change->delete();

        return $this->respond(['removed' => true]);
    }

    /**
     * Show post
     *
     * @param Change $change
     * @return mixed
     */
    public function show(Change $change)
    {
        return $this->respond($change->toArray());
    }

    /**
     * List all news.
     *
     * @return mixed
     */
    public function getChanges()
    {
        $changes = Change::with('author')->latest()->take(15)->get();

        return $this->respond($changes->toArray());
    }
}
