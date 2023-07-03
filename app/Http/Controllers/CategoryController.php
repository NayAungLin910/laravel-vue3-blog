<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Categories list
     *
     * @return Category
     */
    public function index()
    {
        return Category::latest()->get();
    }

    /**
     * Create a new category
     *
     * @param Request $request
     * @return Category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories',
        ]);

        $name = $request->name;

        $category = Category::create([
            'name' => $name
        ]);

        return $category;
    }

    /**
     * Get a specific category
     * 
     * @param Category $category
     * @return Category
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Update a specific category
     *
     * @param Request $request
     * @param Category $category
     * @return Category $category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories',
        ]);

        $name = $request->name;

        $category->name = $name;

        return $category->save();;
    }
}
