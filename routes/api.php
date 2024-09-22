<?php

use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Kategórie
/*
 * GET /categories - získa všetky kategórie
   POST /categories - vytvorí novú kategóriu
   GET /categories/{id} - získa konkrétnu kategóriu
   PUT/PATCH /categories/{id} - aktualizuje kategóriu
   DELETE /categories/{id} - zmaže kategóriu
 */
Route::apiResource('categories', CategoriesController::class);

// Produkty
/*
 * GET /products - získa všetky produkty
   POST /products - vytvorí nový produkt
   GET /products/{id} - získa konkrétny produkt
   PUT/PATCH /products/{id} - aktualizuje produkt
   DELETE /products/{id} - zmaže produkt
 */
Route::apiResource('products', ProductsController::class);


// Objednávky
/*
 * GET /orders - získa všetky objednávky
   POST /orders - vytvorí novú objednávku
   GET /orders/{id} - získa konkrétnu objednávku
   PUT/PATCH /orders/{id} - aktualizuje objednávku
   DELETE /orders/{id} - zmaže objednávku
 */
Route::apiResource('orders', OrdersController::class);

// Podrobnosti objednávky
/*
 * GET /order-items - získa všetky podrobnosti objednávky
   POST /order-items - vytvorí nový podrobnosť objednávky
   GET /order-items/{id} - získa konkrétny podrobnosť objednávky
   PUT/PATCH /order-items/{id} - aktualizuje podrobnosť objednávky
   DELETE /order-items/{id} - zmaže podrobnosť objednávky
 */
Route::apiResource('order-items', OrderItemsController::class);
