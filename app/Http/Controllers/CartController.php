<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getCart(Request $request)
    {
        $cart = $request->cookie('cart');
        $cartItems = $cart ? json_decode($cart, true) : [];
        
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $this->productService->getProductBySlug($item['slug']);
            if ($product) {
                $itemTotal = $product['price'] * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];
                $subtotal += $itemTotal;
            }
        }

        return response()->json([
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => count($items)
        ]);
    }

    public function addToCart(Request $request)
    {
        $slug = $request->slug;
        $quantity = $request->quantity ?? 1;

        $cart = $request->cookie('cart');
        $cartItems = $cart ? json_decode($cart, true) : [];

        // Check if product already in cart
        $found = false;
        foreach ($cartItems as &$item) {
            if ($item['slug'] === $slug) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cartItems[] = [
                'slug' => $slug,
                'quantity' => $quantity
            ];
        }

        $cookie = cookie('cart', json_encode($cartItems), 60 * 24 * 7); // 7 days

        return response()->json(['success' => true, 'count' => count($cartItems)])
            ->cookie($cookie);
    }

    public function updateCart(Request $request)
    {
        $slug = $request->slug;
        $quantity = $request->quantity;

        $cart = $request->cookie('cart');
        $cartItems = $cart ? json_decode($cart, true) : [];

        foreach ($cartItems as $key => &$item) {
            if ($item['slug'] === $slug) {
                if ($quantity <= 0) {
                    unset($cartItems[$key]);
                } else {
                    $item['quantity'] = $quantity;
                }
                break;
            }
        }

        $cartItems = array_values($cartItems); // Re-index array

        $cookie = cookie('cart', json_encode($cartItems), 60 * 24 * 7);

        return response()->json(['success' => true])
            ->cookie($cookie);
    }

    public function removeFromCart(Request $request)
    {
        $slug = $request->slug;

        $cart = $request->cookie('cart');
        $cartItems = $cart ? json_decode($cart, true) : [];

        $cartItems = array_filter($cartItems, function($item) use ($slug) {
            return $item['slug'] !== $slug;
        });

        $cartItems = array_values($cartItems); // Re-index array

        $cookie = cookie('cart', json_encode($cartItems), 60 * 24 * 7);

        return response()->json(['success' => true])
            ->cookie($cookie);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        // Get cart from cookie
        $cart = $request->cookie('cart');
        $cartItems = $cart ? json_decode($cart, true) : [];

        if (empty($cartItems)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Calculate order
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $this->productService->getProductBySlug($item['slug']);
            if ($product) {
                $itemTotal = $product['price'] * $item['quantity'];
                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];
                $subtotal += $itemTotal;
            }
        }

        $shippingCost = 15000; // Fixed shipping cost
        $total = $subtotal + $shippingCost;

        // Create order
        $orderId = 'ORDER-' . strtoupper(Str::random(10));

        $order = Order::create([
            'order_id' => $orderId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
        ]);

        // Create Midtrans transaction
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
            'item_details' => array_merge(
                array_map(function($item) {
                    return [
                        'id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'name' => $item['name'],
                    ];
                }, $items),
                [
                    [
                        'id' => 'SHIPPING',
                        'price' => $shippingCost,
                        'quantity' => 1,
                        'name' => 'Biaya Pengiriman',
                    ]
                ]
            ),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $order->update([
                'snap_token' => $snapToken,
            ]);

            // Clear cart cookie
            $cookie = cookie('cart', json_encode([]), 60 * 24 * 7);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ])->cookie($cookie);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            $order = Order::where('order_id', $request->order_id)->first();
            
            if ($order) {
                $order->update([
                    'transaction_id' => $request->transaction_id,
                    'transaction_status' => $request->transaction_status,
                    'payment_type' => $request->payment_type,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}