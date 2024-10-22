<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\ProductsController;
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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => (new App\Http\Controllers\ProductsController)->filter(),
    ]);
})->name('home');

// Products
Route::get('/products', function () {
    return Inertia::render('Products/Products');
})->name('products');

// Product
Route::get('/products/{product}', function (Product $product) {
    return Inertia::render('Products/Product', [
        'product' => $product,
    ]);
})->name('product');


// Checkout
Route::get('/checkout', function () {
    return Inertia::render('Cart/Checkout');
})->name('checkout');

// Orders
Route::get('/orders', function () {
    return Inertia::render('/Orders/Orders');
})->name('orders')->middleware('auth');

// Dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Cart
Route::get('/cart', [CartController::class, 'cartPage'])->name('cart.page');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/complete', [CartController::class, 'complete'])->name('cart.complete');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart-info', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/remove', [CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/item/quantity', [CartController::class, 'setQuantity'])->name('cart.set.quantity');

// Other controller (for frontend)
Route::get('/filter-content', [OtherController::class, 'getFilterContent'])->name('filter.content');

// Products filtered by parameters
Route::get('/products-filtered', [ProductsController::class, 'filterProducts'])->name('products.filter');

Route::get('/shipping-rates', [OtherController::class, 'getShippingRates'])->name('shipping.rates');
Route::get('/payment-methods', [OtherController::class, 'getPaymentMethods'])->name('payment.methods');
