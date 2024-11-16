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

        return Inertia::render('Settings/Eshop', [
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
                    if(!empty($value['custom'])) {
                        unset($methods[$key]);
                    }
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
                    $methods[$key]['nameEn'] = $value['nameEn'];
                    $methods[$key]['description'] = $value['description'];
                    $methods[$key]['descriptionEn'] = $value['descriptionEn'];
                    $methods[$key]['active'] = $value['active'];

                    if(!empty($request->paymentMethod['imagePath'])) {
                        $methods[$key]['image'] = $request->paymentMethod['image'];
                        $methods[$key]['imagePath'] = $request->paymentMethod['imagePath'];
                    }
                    continue;
                }
            }
        } else {
           $methods[] = [
               'name' => $request->paymentMethod['name'],
               'nameEn' => $request->paymentMethod['nameEn'],
               'id' => count($methods) + 1,
               'custom' => true,
               'active' => true,
               'description' => $request->paymentMethod['description'],
               'descriptionEn' => $request->paymentMethod['descriptionEn'],
               'image' => !empty($request->paymentMethod['image']) ? $request->paymentMethod['image'] : null,
               'imagePath' => !empty($request->paymentMethod['imagePath']) ? $request->paymentMethod['imagePath'] : null,
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
                    if(!empty($value['custom'])) {
                        unset($methods[$key]);
                    }
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
                    $methods[$key]['nameEn'] = $value['nameEn'];
                    $methods[$key]['description'] = $value['description'];
                    $methods[$key]['descriptionEn'] = $value['descriptionEn'];
                    $methods[$key]['active'] = $value['active'];

                    if(!empty($request->shippingMethod['imagePath'])) {
                        $methods[$key]['image'] = $request->shippingMethod['image'];
                        $methods[$key]['imagePath'] = $request->shippingMethod['imagePath'];
                    }
                    continue;
                }
            }
        } else {
            $methods[] = [
                'name' => $request->shippingMethod['name'],
                'nameEn' => $request->shippingMethod['nameEn'],
                'id' => count($methods) + 1,
                'price' => $request->shippingMethod['price'],
                'custom' => true,
                'active' => true,
                'description' => $request->shippingMethod['description'],
                'descriptionEn' => $request->shippingMethod['descriptionEn'],
                'image' => !empty($request->shippingMethod['image']) ? $request->shippingMethod['image'] : null,
                'imagePath' => !empty($request->shippingMethod['imagePath']) ? $request->shippingMethod['imagePath'] : null,
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
