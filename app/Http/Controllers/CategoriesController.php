<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    // Získaj všetky kategórie
    public function index()
    {
        return Category::all();
    }

    // Vytvor novú kategóriu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
        ]);

        return Category::createCategory($validated);
    }

    // Získaj konkrétnu kategóriu
    public function show($id)
    {
        return Category::findOrFail($id);
    }

    // Aktualizuj existujúcu kategóriu
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $id,
        ]);

        $category->updateCategory($validated);
        return $category;
    }

    // Zmaž kategóriu
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->deleteCategory();
        return response()->noContent();
    }
}
