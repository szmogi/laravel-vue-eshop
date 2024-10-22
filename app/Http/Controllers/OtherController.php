<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    // Get filter content
    public function getFilterContent()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return response()->json([
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }


    function getShippingRates()
    {
        //$shippingRates = ShippingRate::all();

        $shippingRates = [
            [
                'id' => 1,
                'name' => 'Standard Shipping',
                'price' => 10,
            ],
            [
                'id' => 2,
                'name' => 'Express Shipping',
                'price' => 20,
            ],
        ];
        return response()->json($shippingRates);
    }

    function getPaymentMethods()
    {
        //$paymentMethods = PaymentMethod::all();


         $paymentMethods = [
            [
                'id' => 1,
                'name' => 'PayPal',
            ],
            [
                'id' => 2,
                'name' => 'Credit Card',
            ],
        ];
        return response()->json($paymentMethods);
    }
}
