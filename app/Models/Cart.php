<?php

namespace App\Models;

use App\Models\Cart as BaseCart;

class Cart extends BaseCart
{
    /**
     * Get the total quantity of items in the cart for the current user or session.
     *
     * @return int
     */
    public static function getTotalQuantity(): int
    {
        $query = auth()->check()
            ? self::where('account_id', auth()->user()->id)
            : self::where('session_id', session()->getId());

        return (int) $query->sum('qty');
    }

    /**
     * Get the total price of all items in the cart for the current user or session.
     *
     * @return float
     */
    public static function getTotal(): float
    {
        $query = auth()->check()
            ? self::where('account_id', auth()->user()->id)
            : self::where('session_id', session()->getId());

        return (float) $query->sum('total');
    }
}
