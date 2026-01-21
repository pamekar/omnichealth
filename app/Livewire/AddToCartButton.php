<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Product;

class AddToCartButton extends Component
{
    public $productId;
    public $quantity = 1;
    public $large = false;

    public function mount($productId, $large = false)
    {
        $this->productId = $productId;
        $this->large = $large;
    }

    public function addToCart()
    {
        $product = Product::find($this->productId);

        if (!$product) {
            $this->dispatch('show-notification', message: 'Product not found!', type: 'error');
            return;
        }

        $cartQuery = auth()->check()
            ? Cart::where('account_id', auth()->user()->id)
            : Cart::where('session_id', session()->getId());

        $cartItem = $cartQuery->where('product_id', $this->productId)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $this->quantity);
        } else {
            Cart::create([
                'product_id' => $product->id,
                'account_id' => auth()->check() ? auth()->user()->id : null,
                'session_id' => !auth()->check() ? session()->getId() : null,
                'item' => $product->name,
                'price' => $product->price,
                'qty' => $this->quantity,
                'total' => $product->price * $this->quantity,
            ]);
        }

        $this->dispatch('show-notification', message: $product->name . ' added to cart!', type: 'success');
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}