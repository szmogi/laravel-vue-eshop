<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrdersController extends Controller
{

    protected $user = null;

    protected $order = null;

    protected $userId = null;

    protected $status = [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
        'refunded' => 'Refunded',
    ];

    public function __construct()
    {
        $this->user = ! auth()->check() ? null : auth()->user();
        $this->orderId = !empty(Auth::id()) ? Auth::id() : null;
    }

    // Získaj všetky objednávky
    public function index()
    {
        return Order::with('items.product')->get();
    }

    // Vytvor novú objednávku
    public function store(Request $request)
    {
        $this->userId = !empty(Auth::id()) ? Auth::id() : null;

        $order = [
            'user_id' => $this->userId,
            'total' => $request->order['total'],
            'status' => 'pending',
            'data' => json_encode($request->order),
        ];

        $this->order = Order::createOrder($order);
        if($this->order) {
            foreach ($request->order['orderItems'] as $item) {
                $item['order_id'] = $this->order->id;
                OrderItem::createOrderItem($item);
            }

            $this->order->updateOrder(['status' => 'completed']);
            return response()->json(['success' => 'Order created successfully!', 'order' => $request->order, 'orderId' => $this->order->id]);
        }

        return response()->json(['error' => 'Order creation failed!', 'order' => []]);
    }


    // Získaj konkrétnu objednávku

    /**
     * @param $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);
        $this->order->data = json_decode($this->order->data);

        return Inertia::render('Dashboard', [
            'orders' => $this->order,
            'tableOrders' => false,
            'orderDetails' => true,
        ]);
    }

    /**
     * @return false|string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getStatusShow(): bool|string
    {
        return json_encode($this->status);
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
