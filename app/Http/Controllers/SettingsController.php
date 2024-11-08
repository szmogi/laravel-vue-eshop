<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{


    public function __construct()
    {
        $this->user = !auth()->check() ? null : auth()->user();
    }


    // Získajte  settings eshop
    public function view()
    {
        $status = Config::where('key', 'eshop-status')->first();
        $paymentMethod = Config::where('key', 'eshop-payment-method')->first();
        $shippingMethod = Config::where('key', 'eshop-shipping-method')->first();
        return Inertia::render('Settings/eshop', [
            'vat' => env('SHOP_VAT'),
            'status' => !empty($status) ? $status->value : [],
            'paymentMethod' => !empty($paymentMethod) ? $paymentMethod->value : [],
            'shippingMethod' => !empty($shippingMethod) ? $shippingMethod->value : [],
            'user' => $this->user,
        ]);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsOrderStatus($request)
    {
        $config = Config::where('key', 'eshop-status')->first();
        $config->value = array_merge($config->value, $request->orderStatus);
        $config->save();
        return response()->json($config->value);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsPaymentMethod($request)
    {
        $config = Config::where('key', 'eshop-payment-method')->first();
        $config->value = array_merge($config->value, $request->paymentMethod);
        $config->save();
        return response()->json($config->value);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsShippingMethod($request)
    {
        $config = Config::where('key', 'eshop-shipping-method')->first();
        $config->value = array_merge($config->value, $request->shippingMethod);
        $config->save();
        return response()->json($config->value);
    }

    public function getSettings()
    {
        return response()->json([
            'vat' => env('SHOP_VAT'),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'vat' => 'required|numeric',
        ]);

        $this->validateAdmin();

        env('SHOP_VAT', $request->vat);

        return response()->json([
            'vat' => env('SHOP_VAT'),
        ]);
    }

}
