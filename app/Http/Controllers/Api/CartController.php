<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {
    }


    public function index()
    {
        $cart = $this->cartService->getCart();

        return response()->json([
            'success' => true,
            'data' => $cart
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);


        $item = $this->cartService->addToCart(
            $request->product_id,
            $request->quantity ?? 1
        );


        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data' => $item
        ]);
    }


    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);


        $item = $this->cartService->updateCartItem(
            $itemId,
            $request->quantity
        );


        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => $item
        ]);
    }

    public function destroy($itemId)
    {
        $removed = $this->cartService->removeCartItem($itemId);


        if (!$removed) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }
}
