<div class="container checkout-container">
    <div class="checkout-grid">
        <!-- Checkout Form -->
        <div class="checkout-form-card">
            <h1 class="checkout-title">Checkout</h1>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" wire:model="first_name" id="first_name" class="form-control" required>
                    @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" wire:model="last_name" id="last_name" class="form-control" required>
                    @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" wire:model="email" id="email" class="form-control" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" wire:model="phone" id="phone" class="form-control" required>
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <textarea wire:model="address" id="address" class="form-control" rows="3" required></textarea>
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
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
                        <span class="item-price">₦{{ number_format($item->price * $item->qty, 2) }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="order-total">
                <span>Total</span>
                <span>₦{{ number_format(\App\Models\Cart::getTotal(), 2) }}</span>
            </div>

            <button wire:click="process" wire:loading.attr="disabled" class="btn btn-primary btn-block">
                <span wire:loading.remove>Pay with Paystack</span>
                <span wire:loading>Processing...</span>
            </button>
            
            @if(session()->has('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>