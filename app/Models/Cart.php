<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $account_id
 * @property integer $product_id
 * @property string $session_id
 * @property string $item
 * @property float $price
 * @property float $discount
 * @property float $vat
 * @property float $qty
 * @property float $total
 * @property string $note
 * @property mixed $options
 * @property boolean $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Product $product
 */

class Cart extends \TomatoPHP\FilamentEcommerce\Models\Cart
{
    /**
     * @var array
     */
    protected $fillable = ['account_id', 'product_id', 'session_id', 'item', 'price', 'discount', 'vat', 'qty', 'total', 'note', 'options', 'is_active', 'created_at', 'updated_at'];

    protected $casts = [
        "is_active" => "boolean",
        "options" => "json"
    ];

    /**
     * Get the content of the cart for the current user or session.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getContent()
    {
        $query = auth()->check()
            ? self::where('account_id', auth()->user()->id)
            : self::where('session_id', session()->getId());

        return $query->with('product')->get();
    }

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

    /**
     * Clear the cart for the current user or session.
     *
     * @return void
     */
    public static function clear()
    {
        $query = auth()->check()
            ? self::where('account_id', auth()->user()->id)
            : self::where('session_id', session()->getId());

        $query->delete();
    }

    /**
     * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('filament-accounts.model'));
    }

    /**
     * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
