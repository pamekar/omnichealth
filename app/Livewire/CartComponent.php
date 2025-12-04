<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartComponent extends Component
{
    public function render()
    {
        $cartItems = Cart::getContent();
        return view('livewire.cart-component', compact('cartItems'))
            ->extends('layouts.app')
            ->section('content')
            ->title('Your Shopping Cart - OMNIC Medical Store');
    }

    public function updateQuantity($id, $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        $cartItem = Cart::find($id);

        if ($cartItem) {
             $cartItem->update([
                 'qty' => $quantity,
                 'total' => $cartItem->price * $quantity
             ]);

            session()->flash('success', 'Cart updated successfully!');
        }
    }

    public function removeItem($id)
    {
        $cartItem = Cart::find($id);
        if ($cartItem) {
            $cartItem->delete();
            session()->flash('success', 'Item removed from cart!');
        }
    }

    public function clearCart()
    {
        Cart::clear();
        session()->flash('success', 'All items cleared from cart!');
    }
}
