<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use TomatoPHP\TomatoEcommerce\Models\Product;

class CartController extends Controller
{
    public function cartList()
    {
        $cartItems = auth()->check()
            ? Cart::where('account_id', auth()->user()->id)->get()
            : Cart::where('session_id', session()->getId())->get();

        return view('cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartQuery = auth()->check()
            ? Cart::where('account_id', auth()->user()->id)
            : Cart::where('session_id', session()->getId());

        $cartItem = $cartQuery->where('product_id', $request->id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $request->quantity);
        } else {
            Cart::create([
                'product_id' => $request->id,
                'account_id' => auth()->check() ? auth()->user()->id : null,
                'session_id' => !auth()->check() ? session()->getId() : null,
                'item' => $request->name,
                'price' => $request->price,
                'qty' => $request->quantity,
                'total' => $request->price * $request->quantity,
            ]);
        }

        session()->flash('success', 'Product added to cart successfully!');

        return redirect()->back();
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::find($request->id);

        // Optional: Add policy/gate to ensure user owns this cart item
        if ($cartItem) {
            $cartItem->update(['qty' => $request->quantity]);
            session()->flash('success', 'Cart updated successfully!');
        }

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        $request->validate(['id' => 'required|exists:carts,id']);

        $cartItem = Cart::find($request->id);

        // Optional: Add policy/gate to ensure user owns this cart item
        if ($cartItem) {
            $cartItem->delete();
            session()->flash('success', 'Item removed from cart!');
        }

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        $cartQuery = auth()->check()
            ? Cart::where('account_id', auth()->user()->id)
            : Cart::where('session_id', session()->getId());

        $cartQuery->delete();

        session()->flash('success', 'All items cleared from cart!');

        return redirect()->route('cart.list');
    }
}
