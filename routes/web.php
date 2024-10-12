<?php

use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $products = Product::with('images', 'category', 'color', 'size','variants')->paginate(12);

    // Eager load the variants separately (not recommended due to multiple queries)
    foreach ($products as $product) {
        foreach ($product->variants as $key => $variant) {
            $product->variants[$key] = Product::with( 'color', 'size')->find($variant->id);
        }
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products,
    ]);
});

Route::get('/products', function () {
    return Inertia::render('Products/Products');
})->name('products');

Route::get('/products/{product}', function (Product $product) {
    return Inertia::render('Products/Product', [
        'product' => $product,
    ]);
})->name('product');


Route::get('/cart', function () {
    return Inertia::render('Cart/Cart');
})->name('cart');


Route::get('/checkout', function () {
    return Inertia::render('Cart/Checkout');
})->name('checkout');


Route::get('/orders', function () {
    return Inertia::render('/Orders/Orders');
})->name('orders')->middleware('auth');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

