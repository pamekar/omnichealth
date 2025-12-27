<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Livewire\Attributes\On;

class CartCount extends Component
{
    #[On('cartUpdated')]
    public function render()
    {
        $count = Cart::getTotalQuantity();
        return view('livewire.cart-count', compact('count'));
    }
}