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

    // Filter products by master_id (not variants) and return paginated
    public function filter()
    {
        $products = Product::with('images','variants')
            ->selectRaw('MAX(id) as id, MAX(name) as name, MAX(price) as price, master_id')
            ->groupBy('master_id')
            ->paginate(12);

        return $this->returnProducts($products);
    }

    // Filter products by master_id (not variants) and return paginated (for frontend)
    public function filterProducts(request $request)
    {
        $categories = $request->get('category');
        $colors = $request->get('color');
        $sizes = $request->get('size');

        $products = Product::with('images','variants')
            ->selectRaw('MAX(id) as id, MAX(name) as name, MAX(price) as price, master_id')
            ->where(function ($query) use ($categories, $colors, $sizes) {
                if ($categories) {
                    $query->whereIn('category_id', explode(',', $categories));
                }
                if ($colors) {
                    $query->whereIn('color_id', explode(',', $colors));
                }
                if ($sizes) {
                    $query->whereIn('size_id', explode(',', $sizes));
                }
            })
            ->groupBy('master_id')
            ->paginate(12);

        // Eager load the variants separately (not recommended due to multiple queries) and return

        return response()->json($this->returnProducts($products));
    }


    /**
     * Return products
     */
    private function returnProducts($products): array
    {
        $newProducts = [];
        $count = 0;
        foreach ($products as $product) {
            $newProducts[$product->id] = Product::with('images','color','size','category')->find($product->id);
            $count++;
            foreach ($product->variants as $key => $variant) {
                $product->variants[$key] = Product::with( 'color', 'size')->find($variant->id);
            }

            $newProducts[$product->id]->variants = $product->variants;
        }
        $results['count'] = $count;
        $results['products'] = $newProducts;
        return $results;
    }
}

