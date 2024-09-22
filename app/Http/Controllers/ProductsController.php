<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // Získaj všetky produkty
    public function index()
    {
        return Product::with('category')->get();
    }

    // Vytvor nový produkt
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products',
        ]);

        return Product::createProduct($validated);
    }

    // Získaj konkrétny produkt
    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    // Aktualizuj existujúci produkt
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
        ]);

        $product->updateProduct($validated);
        return $product;
    }

    // Zmaž produkt
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->deleteProduct();
        return response()->noContent();
    }
}
