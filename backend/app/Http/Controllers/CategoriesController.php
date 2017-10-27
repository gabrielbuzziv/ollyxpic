<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Items;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        $categories = Categories::get();

        return $categories;
    }

    public function items(Categories $category)
    {
        return Items::with('categories')->first();

        return $category;
    }
}
