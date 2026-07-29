<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    public function getUserCart($userId)
    {
        return Cart::with('items.product')
            ->where('user_id', $userId)
            ->first();
    }


    public function createCart($userId)
    {
        return Cart::create([
            'user_id' => $userId,
        ]);
    }


    public function addItem($cartId, $productId, $quantity)
    {
        return CartItem::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }


    public function findItem($cartId, $productId)
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }


    public function updateQuantity($item, $quantity)
    {
        $item->update([
            'quantity' => $quantity
        ]);

        return $item;
    }


    public function updateItemQuantity($cartId, $itemId, $quantity)
    {
        $item = CartItem::where('cart_id', $cartId)
            ->where('id', $itemId)
            ->first();

        if (!$item) {
            return null;
        }

        $item->update([
            'quantity' => $quantity
        ]);

        return $item;
    }

    public function removeItem($cartId, $itemId)
    {
        $item = CartItem::where('cart_id', $cartId)
            ->where('id', $itemId)
            ->first();

        if (!$item) {
            return false;
        }

        $item->delete();

        return true;
    }
}
