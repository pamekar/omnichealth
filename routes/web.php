<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Product;

Route::get('/', function () {
    $products = Product::where('is_trend', true)->paginate(12);
    return view('welcome', compact('products'));
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/about', [ShopController::class, 'about'])->name('shop.about');
Route::get('/privacy', [ShopController::class, 'privacy'])->name('shop.privacy');

Route::get('cart', \App\Livewire\CartComponent::class)->name('cart.list');
// Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');

Route::get('checkout', \App\Livewire\CheckoutComponent::class)->name('checkout.index');
// Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/error', [CheckoutController::class, 'error'])->name('checkout.error');
