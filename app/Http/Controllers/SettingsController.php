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
        $vat = Config::where('key', 'eshop-vat')->first();
        $status = Config::where('key', 'eshop-status')->first();
        $paymentMethod = Config::where('key', 'eshop-payment-method')->first();
        $shippingMethod = Config::where('key', 'eshop-shipping-method')->first();

        return Inertia::render('Settings/eshop', [
            'vat' => $vat ? $vat->value : env('SHOP_VAT'),
            'status' => !empty($status) ? $status->value : [],
            'paymentMethod' => !empty($paymentMethod) ? $paymentMethod->value : [],
            'shippingMethod' => !empty($shippingMethod) ? $shippingMethod->value : [],
            'user' => $this->user,
        ]);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsVat(Request $request)
    {
        $config = Config::where('key', 'eshop-vat')->first();
        $config->value = $request->vat;
        $config->save();
        return response()->json($config->value);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsOrderStatus(Request $request ): \Illuminate\Http\JsonResponse
    {
        $config = Config::where('key', 'eshop-status')->first();
        $status = $config->value;

        if(!empty($request->remove)) {
            foreach ($status as $key => $value) {
                if($key == mb_strtolower($request->status)) {
                    unset($status[$key]);
                }
            }
            $config->value = $status;
            Config::where('key', 'eshop-status')->update(['value' => serialize($config->value)]);
            return response()->json($config->value);
        }

        if(!empty($request->status['id'])) {
           foreach ($status as $key => $value) {
               if($key == $request->status['id']) {
                   unset($status[$key]);
                   $status[mb_strtolower($request->status['name'])] =  $request->status['name'];
               }
           }
        } else {
            $status[mb_strtolower($request->status)] = $request->status;
        }

        ksort($status);
        $config->value = $status;
        Config::where('key', 'eshop-status')->update(['value' => serialize($config->value)]);
        return response()->json($config->value);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsPaymentMethod(Request $request)
    {
        $config = Config::where('key', 'eshop-payment-method')->first();
        $methods = $config->value;

        if(!empty($request->remove)) {
            foreach ($methods as $key => $value) {
                if($value['id'] == $request->paymentMethod['id'] ) {
                    unset($methods[$key]);
                }
            }
            $config->value = $methods;
            Config::where('key', 'eshop-payment-method')->update(['value' => serialize($config->value)]);
            return response()->json($config->value);
        }

        if(!empty($request->paymentMethod['id'])) {
            foreach ($methods as $key => $value) {
                if($value['id'] == $request->paymentMethod['id'] ) {
                    $methods[$key]['name'] = $value['name'];
                    continue;
                }
            }
        } else {
           $methods[] = [
               'name' => $request->paymentMethod['name'],
               'id' => count($methods) + 1,
           ];
        }
        $config->value = $methods;
        Config::where('key', 'eshop-payment-method')->update(['value' => serialize($config->value)]);
        return response()->json($config->value);
    }

    // Ak chceš pridať novú konfiguráciu
    public function settingsShippingMethod(Request $request)
    {
        $config = Config::where('key', 'eshop-shipping-method')->first();
        $methods = $config->value;

        if(!empty($request->remove)) {
            foreach ($methods as $key => $value) {
                if($value['id'] == $request->shippingMethod['id'] ) {
                    unset($methods[$key]);
                }
            }
            $config->value = $methods;
            Config::where('key', 'eshop-shipping-method')->update(['value' => serialize($config->value)]);
            return response()->json($config->value);
        }

        if(!empty($request->shippingMethod['id'])) {
            foreach ($methods as $key => $value) {
                if($value['id'] == $request->shippingMethod['id'] ) {
                    $methods[$key]['price'] = $value['price'];
                    $methods[$key]['name'] = $value['name'];
                    continue;
                }
            }
        } else {
            $methods[] = [
                'name' => $request->shippingMethod['name'],
                'id' => count($methods) + 1,
                'price' => $request->shippingMethod['price'],
            ];
        }
        $config->value = $methods;
        Config::where('key', 'eshop-shipping-method')->update(['value' => serialize($config->value)]);

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
