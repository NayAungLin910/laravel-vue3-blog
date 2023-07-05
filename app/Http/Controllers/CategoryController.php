<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::latest()->get();
    }

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

    public function show(Category $category)
    {
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories',
        ]);

        $name = $request->name;

        $category->name = $name;

        return $category->save();;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response([
            'successMessage' => 'The category has been deleted successfully!'
        ]);
    }
}
