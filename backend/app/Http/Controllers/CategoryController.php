<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\WikiItems;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    /**
     * Get categories from database.db and syncronize with mysql database.
     *
     * @return $this
     */
    public function syncronize()
    {
        $categories = WikiItems::select('category')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->groupBy('category')
            ->get();

        return $categories->each(function ($category) {
            $data = Category::firstOrNew(['name' => str_slug($category->category)]);
            $data->title = $category->category;
            $data->save();
        });
    }

    /**
     * Get all categories.
     *
     * @return mixed
     */
    public function index()
    {
        $categories = Category::get();

        return $this->respond($categories->toArray());
    }

    /**
     * Show item.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        $category = Category::with('items')->find($category->id);

        return $this->respond($category->toArray());
    }

    /**
     * Update Category
     *
     * @param Category $category
     * @return mixed
     */
    public function update(Category $category)
    {
        $category->update(request()->all());

        return $this->respond($category->toArray());
    }

    /**
     * Get all categories.
     *
     * @return mixed
     */
    public function usables()
    {
        $categories = Category::usable()->get();

        return $this->respond($categories->toArray());
    }

    /**
     * Toggle Usable.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleUsable(Category $category)
    {
        $category->usable = $category->usable ? 0 : 1;
        $category->save();

        return $this->respond($category->toArray());
    }

    /**
     * Get categories to blacklist
     *
     * @return mixed
     */
    public function blacklist()
    {
        $categories = Category::whereNotIn('id', [4, 12, 14, 18, 22, 23, 24, 26, 47])
            ->orderBy('title', 'asc')
            ->get();

        return $this->respond($categories->toArray());
    }
}
