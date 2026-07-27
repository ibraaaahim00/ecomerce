<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * ✅ Checkout من السلة
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 422);
        }

        // ✅ Check stock
        foreach ($cartItems as $item) {

            if (!$item->product) {
                return response()->json([
                    'message' => 'Product not found'
                ], 404);
            }

            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Product '{$item->product->title}' is out of stock"
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($request, $cartItems, $validated) {

            $totalPrice = 0;

            foreach ($cartItems as $item) {
                $totalPrice += $item->product->price * $item->quantity;
            }

            // ✅ Create Order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'address' => $validated['address'],
            ]);

            // ✅ Create Order Items
            foreach ($cartItems as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // ✅ Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // ✅ Clear Cart
            CartItem::where('user_id', $request->user()->id)->delete();

            return $order->load('items.product', 'user');
        });

        return response()->json([
            'message' => 'Order placed successfully ✅',
            'order' => $order,
        ], 201);
    }

    /**
     * ✅ My Orders
     */
    public function myOrders(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * ✅ Manual Store Order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $productIds = collect($validated['items'])->pluck('product_id');

        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // ✅ Check stock
        foreach ($validated['items'] as $item) {

            $product = $products->get($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Product '{$product->title}' does not have enough stock"
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($request, $validated, $products) {

            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                $totalPrice += $product->price * $item['quantity'];
            }

            // ✅ Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'address' => $validated['address'],
            ]);

            // ✅ Create items
            foreach ($validated['items'] as $item) {

                $product = $products->get($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // ✅ Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            return $order->load('items.product', 'user');
        });

        return response()->json([
            'message' => 'Order created successfully ✅',
            'order' => $order,
        ], 201);
    }

    /**
     * ✅ Admin - All Orders
     */
    public function allOrders()
    {
        $orders = Order::with([
            'user',
            'items.product'
        ])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * ✅ Admin - Update Status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        return response()->json([
            'message' => 'Order updated successfully ✅',
            'order' => $order->load('items.product', 'user'),
        ]);
    }
}
