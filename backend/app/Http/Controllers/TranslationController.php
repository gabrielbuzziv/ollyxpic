<?php

namespace App\Http\Controllers;

use App\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{

    /**
     * Get all translations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $translations = Translation::orderBy('fixed', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return $this->respond($translations->toArray());
    }

    /**
     * Create translation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = [
            'from' => request('from'),
            'to' => request('to'),
            'fixed' => request('fixed') ?: 0,
        ];
        $translation = Translation::create($data);

        return $this->respond($translation->toArray());
    }

    /**
     * Update translation.
     *
     * @param Translation $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Translation $translation)
    {
        $data = [
            'from' => request('from'),
            'to' => request('to'),
            'fixed' => 1,
        ];
        $translation->update($data);

        return $this->respond($translation->toArray());
    }

    /**
     * Remove translation.
     *
     * @param Translation $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Translation $translation)
    {
        $translation->delete();

        return $this->respond(['removed' => true]);
    }
}
