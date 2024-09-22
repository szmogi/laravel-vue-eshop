<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // Získaj všetky produkty
    public function index()
    {
        return Product::with(['category', 'color', 'size'])->get();
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
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'master_id' => 'nullable|exists:products,id',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // Získaj konkrétny produkt
    public function show($id)
    {
        $product = Product::with(['category', 'color', 'size'])->findOrFail($id);
        return response()->json($product);
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
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'master_id' => 'nullable|exists:products,id',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // Získaj varianty produktu
    public function variants($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product->variants);
    }

    // Zmaž produkt
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->noContent();
    }
}

