@extends('layouts.app')

@section('title', 'Checkout - OMNIC Medical Store')

@section('content')
    <div class="container checkout-container">
        <div class="checkout-grid">
            <!-- Checkout Form -->
            <div class="checkout-form-card">
                <h1 class="checkout-title">Checkout</h1>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Notes</label>
                        <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Mobile only button (optional, but cleaner if kept with summary) -->
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary-card">
                <h2 class="checkout-title" style="font-size: 1.5rem;">Your Order</h2>
                <div class="order-items-list">
                    @foreach($cartItems as $item)
                        <div class="order-item">
                            <div>
                                <span class="item-name">{{ $item->product->name ?? $item->item }}</span>
                                <span class="item-qty">x{{ $item->qty }}</span>
                            </div>
                            @if(config('filament-ecommerce.enable_pricing'))
                                <span class="item-price">₦{{ number_format($item->price * $item->qty, 2) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if(config('filament-ecommerce.enable_pricing'))
                    <div class="order-total">
                        <span>Total</span>
                        <span>₦{{ number_format(App\Models\Cart::getTotal(), 2) }}</span>
                    </div>
                @endif

                <button type="submit" onclick="document.querySelector('form').submit()" class="btn btn-primary btn-block">
                    {{ config('filament-ecommerce.enable_pricing') ? 'Pay with Flutterwave' : 'Request Quote' }}
                </button>
            </div>
        </div>
    </div>
@endsection
