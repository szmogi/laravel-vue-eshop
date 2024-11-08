<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Config;
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


    // Získajte shipping rates
    public  function getShippingRates()
    {

        $shippingRates = Config::where('key', 'eshop-shipping-method')->first();
        if(!$shippingRates) {
            $shippingRates = $this->defaultShippingMethod();
        }
        return response()->json($shippingRates->value);
    }

    // Získajte payment methods
    public function getPaymentMethods()
    {
        $paymentMethods = Config::where('key', 'eshop-payment-method')->first();

        if(!$paymentMethods) {
            $paymentMethods = $this->defaultPaymentMethod();
        }

        return response()->json($paymentMethods->value);
    }


    private function defaultPaymentMethod()
    {
        $paymentMethod = Config::where('key', 'eshop-payment-method')->first();
        if(!$paymentMethod) {
            $paymentMethod = Config::create([
                'key' => 'eshop-payment-method',
                'value' => [
                    [
                        'id' => 1,
                        'name' => 'PayPal',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Credit Card',
                    ],
                ],
            ]);
        }
        return $paymentMethod;
    }

    private function defaultShippingMethod()
    {
        $shippingMethod = Config::where('key', 'eshop-shipping-method')->first();
        if(!$shippingMethod) {
            $shippingMethod = Config::create([
                'key' => 'eshop-shipping-method',
                'value' => [
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
                ],
            ]);
        }
        return $shippingMethod;
    }
}
