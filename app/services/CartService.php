<?php

namespace App\Services;

use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function __construct(
        private CartRepository $cartRepository
    ) {
    }


    public function addToCart($productId, $quantity = 1)
    {
        $userId = Auth::id();


        $cart = $this->cartRepository->getUserCart($userId);


        if (!$cart) {
            $cart = $this->cartRepository->createCart($userId);
        }


        $item = $this->cartRepository->findItem(
            $cart->id,
            $productId
        );


        if ($item) {

            return $this->cartRepository->updateQuantity(
                $item,
                $item->quantity + $quantity
            );

        }


        return $this->cartRepository->addItem(
            $cart->id,
            $productId,
            $quantity
        );
    }


    public function getCart()
    {
        return $this->cartRepository->getUserCart(Auth::id());
    }


    public function updateCartItem($itemId, $quantity)
    {
        $cart = $this->cartRepository->getUserCart(Auth::id());

        if (!$cart) {
            return null;
        }

        return $this->cartRepository->updateItemQuantity(
            $cart->id,
            $itemId,
            $quantity
        );
    }

    public function removeCartItem($itemId)
    {
        $cart = $this->cartRepository->getUserCart(Auth::id());

        if (!$cart) {
            return false;
        }

        return $this->cartRepository->removeItem(
            $cart->id,
            $itemId
        );
    }
}
