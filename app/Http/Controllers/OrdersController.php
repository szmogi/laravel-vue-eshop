<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    // Získaj všetky objednávky
    public function index()
    {
        return Order::with('items.product')->get();
    }

    // Vytvor novú objednávku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        return Order::createOrder($validated);
    }

    // Získaj konkrétnu objednávku
    public function show($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }

    // Aktualizuj existujúcu objednávku
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'total' => 'sometimes|numeric',
            'status' => 'sometimes|string',
        ]);

        $order->updateOrder($validated);
        return $order;
    }

    // Zmaž objednávku
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->deleteOrder();
        return response()->noContent();
    }
}
