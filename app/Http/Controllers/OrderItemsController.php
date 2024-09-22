<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    // Získaj všetky podrobnosti objednávky
    public function index()
    {
        return OrderItem::with('order', 'product')->get();
    }

    // Vytvor nový podrobnosť objednávky
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        return OrderItem::createOrderItem($validated);
    }

    // Získaj konkrétny podrobnosť objednávky
    public function show($id)
    {
        return OrderItem::with('order', 'product')->findOrFail($id);
    }

    // Aktualizuj existujúci podrobnosť objednávky
    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $validated = $request->validate([
            'quantity' => 'sometimes|integer',
            'price' => 'sometimes|numeric',
        ]);

        $orderItem->updateOrderItem($validated);
        return $orderItem;
    }

    // Zmaž podrobnosť objednávky
    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->deleteOrderItem();
        return response()->noContent();
    }
}
