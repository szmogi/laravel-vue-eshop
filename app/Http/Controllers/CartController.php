<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{

    // protected $redirectTo = '/';
    protected $redirectTo = '/';

    // protected $user = null;
    protected $user = null;

    // protected $cart = null;
    protected $cart = null;

    // protected $totalSum = 0;
    protected $totalSum = 0;

    // protected $noVat = 0;
    protected $vat = 0;

    // protected $currentUser = null;
    protected $currentUser = null;

    protected $userId = null;


    public function __construct()
    {
        $this->user = !auth()->check() ? null : auth()->user();
        $this->cart = $this->getCartContent();
        $this->vat = env('SHOP_VAT');
        $this->currentUser = Auth::user();
        $this->userId = Auth::id();
    }

    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCartContent()
    {
        $this->currentUser = Auth::user();
        $this->cart = $this->getSessionCart();
        $noVat = 0;
        $this->totalSum = 0;
        if(!empty($this->cart)) {
            foreach ($this->cart as $key => $value) {
                if(!empty($this->cart[$key]['product_id'])) {
                    $product = Product::find($this->cart[$key]['product_id']);
                    $this->cart[$key]['inventoryStatus'] = !$product->stock > 0 ? false : true;
                    $this->cart[$key]['rating'] = rand(1, 5);
                    $this->cart[$key]['category'] = $product->category->name;
                    $this->cart[$key]['price'] = $product->price * $this->cart[$key]['quantity'];
                    $this->cart[$key]['maxQuantity'] = $product->stock;
                    $this->totalSum += $product->price * $this->cart[$key]['quantity'];
                }
            }
        }

        if($this->totalSum > 0) {
            $noVat = round($this->totalSum / $this->vat, 2);

        }

        return array('cart' => $this->cart,
            'totalSum' => round($this->totalSum, 2),
            'noVat' => $noVat,
            'sessionId' => session()->getId(),
            'user' => $this->currentUser,
        );
    }


    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getSessionCart()
    {
        if(!empty(Auth::id())) {
            $this->userId = Auth::id();
            $cart = session()->get('cart-'.$this->userId, []);
            return $cart;
        } else {
            $cart = session()->get('cart', []);
            return $cart;
        }
    }

    /**
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function setSessionCart(): void
    {
        if(!empty(Auth::id())) {
            $this->userId = Auth::id();
            session()->put('cart-'.$this->userId, $this->cart);
        } else {
            session()->put('cart', $this->cart);
        }
    }

    /**
     * @return \Inertia\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * First step of the cart
     */
    public function cartPage()
    {
        $cart = $this->getCartContent();
        return Inertia::render('Cart/Cart', [
            'cartList' => $this->getCartContent(),
            'step' => 1,
            'user' => $this->user,
            'totalSum' => $this->totalSum,
            'noVat' => $cart['noVat'],
        ]);
    }

    /**
     * @return \Inertia\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * Second step of the cart
     */
    public function checkout()
    {
        $cart = $this->getSessionCart();
        return Inertia::render('Cart/Checkout', [
            'cartList' => $cart,
            'step' => 2,
            'user' => $this->user,
        ]);
    }

    /**
     * @return \Inertia\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * Third step of the cart
     */
    public function complete()
    {
        $cart = $this->getSessionCart();
        return Inertia::render('Cart/Complete', [
            'cartList' => $cart,
            'step' => 3,
            'user' => $this->user,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function addToCart(Request $request)
    {
        // Retrieve cart from session or create a new one if it doesn't exist
        $cart = $this->getSessionCart();

        if(!empty(Auth::id())) {
            $this->userId = Auth::id();
        }
        // Item details (for example, from request)
        $id = $request->input('id');
        $productId = $request->input('product_id');
        $name = $request->input('name');
        $price = $request->input('price');
        $quantity = $request->input('quantity', 1); // default quantity is 1

        // Check if the item is already in the cart
        if (isset($cart[$id])) {
            // Update the quantity
            $cart[$id]['quantity'] += $quantity;
        } else {
            // Add the item to the cart
            $cart[$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'product_id' => $productId,
                'user' => $this->userId,
            ];
        }

        // Save updated cart in session
        $this->cart = $cart;
        $this->setSessionCart();
        $cart = $this->getCartContent();

        return response()->json(['success' => 'Item added to cart successfully!', 'cart' => $cart]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function viewCart()
    {
        // Retrieve cart from session
        $cart = $this->getCartContent();
        return response()->json(['cart' => $cart]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setQuantity(Request $request) {
        $id = $request->input('id');
        $quantity = $request->input('quantity');
        $this->cart = $this->getCartContent();

        if($quantity > 0) {
            foreach ($this->cart['cart'] as $key => $item) {
                if($item['product_id'] == $id) {
                    if($quantity > $this->cart['cart'][$key]['maxQuantity']) {
                        $quantity = $this->cart['cart'][$key]['maxQuantity'];
                    }
                    $this->cart['cart'][$key]['quantity'] = $quantity;
                    session()->put('cart', $this->cart['cart']);
                    $this->cart = $this->getCartContent();
                    return response()->json(['message' => 'Item quantity updated successfully!', 'status' => 'success','cart' => $this->cart]);
                }
            }
        }

        return response()->json(['message' => 'Item quantity can not be less than 1!', 'status' => 'error','cart' => $this->cart]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function removeItem(Request $request)
    {
        $id = $request->input('id');
        $cart = $this->getSessionCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->cart = $cart;
            $this->setSessionCart();
        }

        return response()->json(['success' => 'Item removed from cart successfully!', 'cart' => $cart]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function clearCart()
    {
        $this->cart = [];
        $this->setSessionCart();
        return response()->json([]);
    }
}
