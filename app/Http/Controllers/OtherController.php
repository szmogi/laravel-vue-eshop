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
        if(empty($shippingRates->value)) {
            $shippingRates = $this->defaultShippingMethod();
        }
        return response()->json($shippingRates->value);
    }

    // Získajte payment methods
    public function getPaymentMethods()
    {
        $paymentMethods = Config::where('key', 'eshop-payment-method')->first();

        if(empty($paymentMethods->value)) {
            $paymentMethods = $this->defaultPaymentMethod();
        }

        return response()->json($paymentMethods->value);
    }


    private function defaultPaymentMethod()
    {
        $paymentMethod = Config::where('key', 'eshop-payment-method')->first();
        if(empty($paymentMethod)) {
            $paymentMethod = Config::create([
                'key' => 'eshop-payment-method',
                'value' => [
                    [
                        'id' => 1,
                        'name' => 'PayPal',
                        'nameEn' => 'PayPal',
                        'image' => '/images/shipping/courier.png',
                        'description' => '',
                        'descriptionEn' => '',
                        'active' => true,
                    ],
                    [
                        'id' => 2,
                        'name' => 'Credit Card',
                        'nameEn' => 'Credit Card',
                        'image' => '/images/shipping/card.png',
                        'description' => '',
                        'descriptionEn' => '',
                        'active' => true,
                    ],
                    [
                        'id' => 3,
                        'name' => 'Bankový prevod',
                        'nameEn' => 'Bank transfer',
                        'image' => '/images/shipping/bank.png',
                        'description' => 'Objednávka bude odoslaná po prijatej platby.',
                        'descriptionEn' => 'The order will be shipped after payment is received.',
                        'active' => true,
                    ],
                ],
            ]);
        }
        return $paymentMethod;
    }

    private function defaultShippingMethod()
    {
        $shippingMethod = Config::where('key', 'eshop-shipping-method')->first();
        if(empty($shippingMethod)) {
            $shippingMethod = Config::create([
                'key' => 'eshop-shipping-method',
                'value' => [
                    [
                        'id' => 1,
                        'name' => 'Kurier',
                        'price' => 5,
                        'active' => true,
                        'nameEn' => 'Courier',
                        'description' => 'Dodanie do 24 hodín',
                        'descriptionEn' => 'Delivery within 24 hours',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Osobný odber na predajni',
                        'price' => 0,
                        'active' => true,
                        'nameEn' => 'Personal collection at the store',
                        'description' => 'Dodanie do 24 hodín',
                        'descriptionEn' => 'Delivery within 24 hours',
                    ],
                ],
            ]);
        }
        return $shippingMethod;
    }
}
