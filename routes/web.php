<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use App\Models\Order;
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

// Orders
Route::post('/order/add', [OrdersController::class, 'store'])->name('order.add');
Route::get('/order/show/{id}', [OrdersController::class, 'show'])->name('order.show');
Route::get('/order/status', [OrdersController::class, 'getStatusShow'])->name('order.status');

// Dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $orders = Order::with('items.product')->where('user_id', auth()->id())->get()->sortBy('created_at');
        return Inertia::render('Dashboard', [
            'orders' => $orders,
            'tableOrders' => true,
            'orderDetails' => false,
        ]);
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

// Shipping rates
Route::get('/shipping-rates', [OtherController::class, 'getShippingRates'])->name('shipping.rates');

// Payment methods
Route::get('/payment-methods', [OtherController::class, 'getPaymentMethods'])->name('payment.methods');

// Settings
Route::get('/settings/eshop', [SettingsController::class, 'view'])->name('settings.eshop')->middleware('auth');
Route::post('/settings/eshop/vat', [SettingsController::class, 'settingsVat'])->name('settings.eshop.vat')->middleware('auth');
Route::post('/settings/eshop/status', [SettingsController::class, 'settingsOrderStatus'])->name('settings.eshop.status')->middleware('auth');
Route::post('/settings/eshop/payment-method', [SettingsController::class, 'settingsPaymentMethod'])->name('settings.eshop.payment-method')->middleware('auth');
Route::post('/settings/eshop/shipping-method', [SettingsController::class, 'settingsShippingMethod'])->name('settings.eshop.shipping-method')->middleware('auth');

// Upload
Route::post('api/upload', [FileUploadController::class, 'upload']);

Route::get('/api/proxy/exchangerate', function () {
    $response = file_get_contents('https://api.exchangerate-api.com/v4/latest/EUR?app_id='.env('VITE_API_KEY_RATE'));
    return response($response, 200)->header('Content-Type', 'application/json');
});

