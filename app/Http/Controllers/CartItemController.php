<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * عرض سلة اليوزر الحالي
     */
    public function index(Request $request)
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($cartItems);
    }

    /**
     * إضافة منتج للسلة أو تحديث الكمية لو موجود
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id'    => $request->user()->id,
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => $validated['quantity'],
            ]
        );

        return response()->json($cartItem->load('product'), 201);
    }

    /**
     * تحديث كمية في السلة
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // التحقق إن الـ cart item بتاع اليوزر الحالي فقط
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($validated);

        return response()->json($cartItem->load('product'));
    }

    /**
     * حذف item من السلة
     */
    public function destroy(Request $request, CartItem $cartItem)
    {
        // التحقق إن الـ cart item بتاع اليوزر الحالي فقط
        if ($cartItem->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart'
        ]);
    }
}
