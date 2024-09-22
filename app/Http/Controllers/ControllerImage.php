<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;;

class ControllerImage extends Controller
{
    // Zobrazenie produktov
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Zobrazenie produktu
    public function show($id)
    {
        $product = Product::find($id);
        return view('products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'sku' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validácia obrázkov
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public'); // uloženie obrázku
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }
}



