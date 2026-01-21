<div class="cart-page">
    <section class="section">
        <div class="container">
            <h1 class="section-title" data-aos="fade-up">Your Shopping Cart</h1>

            @if(session('success'))
                <div class="alert alert-success" data-aos="fade-up" data-aos-delay="100">
                    {{ session('success') }}
                </div>
            @endif

            @if(count($cartItems) > 0)
                <div class="cart-container" data-aos="fade-up" data-aos-delay="200">
                    <div class="cart-items">
                        <div class="cart-header">
                            <div class="header-product">Product</div>
                            <div class="header-quantity">Quantity</div>
                            @if(config('filament-ecommerce.enable_pricing'))
                                <div class="header-price">Price</div>
                                <div class="header-total">Total</div>
                            @endif
                            <div class="header-remove"></div>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="cart-item" wire:key="item-{{ $item->id }}">
                                <div class="item-product">
                                    <img
                                        src="https://placehold.co/100x100/007bff/white?text={{ urlencode(substr($item->item, 0, 1)) }}"
                                        alt="{{ $item->item }}">
                                    <div>
                                        <p class="item-name">{{ $item->item }}</p>
                                        {{--<small>SKU: MOCKSKU123</small>--}}
                                    </div>
                                </div>
                                <div class="item-quantity">
                                    <div class="quantity-form">
                                        <input type="number"
                                               wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                               value="{{ $item->qty }}"
                                               min="1"
                                               class="quantity-input">
                                    </div>
                                </div>
                                @if(config('filament-ecommerce.enable_pricing'))
                                    <div class="item-price">₦{{ number_format($item->price / 100, 2) }}</div>
                                    <div class="item-total">
                                        ₦{{ number_format(($item->price * $item->qty) / 100, 2) }}</div>
                                @endif
                                <div class="item-remove">
                                    <button wire:click="removeItem({{ $item->id }})" class="btn-remove" aria-label="Remove item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal ({{ \App\Models\Cart::getTotalQuantity() }} items)</span>
                            @if(config('filament-ecommerce.enable_pricing'))
                                <span>₦{{ number_format(\App\Models\Cart::getTotal() / 100, 2) }}</span>
                            @endif
                        </div>
                        @if(config('filament-ecommerce.enable_pricing'))
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>₦10.00</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>₦{{ number_format((\App\Models\Cart::getTotal() / 100) + 10, 2) }}</span>
                        </div>
                        @endif
                        <a href="/checkout" wire:navigate class="btn btn-primary btn-checkout">
                            {{ config('filament-ecommerce.enable_pricing') ? 'Proceed to Checkout' : 'Request Quote' }}
                        </a>
                    </div>
                </div>
                <div class="cart-actions" data-aos="fade-up">
                    <a href="{{ route('shop.index') }}" wire:navigate class="btn-continue-shopping"><i
                            class="fas fa-arrow-left"></i> Continue Shopping</a>
                    <button wire:click="clearCart" class="btn-clear-cart">Clear Cart</button>
                </div>
            @else
                <div class="cart-empty" data-aos="fade-up">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is currently empty.</p>
                    <a href="{{ route('shop.index') }}" wire:navigate class="btn btn-primary">Start Shopping</a>
                </div>
            @endif
        </div>
    </section>

    <style>
        .cart-page .section-title {
            margin-bottom: 2rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            border: 1px solid #c3e6cb;
        }

        .cart-container {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .cart-items {
            flex-grow: 1;
            background: var(--surface-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .cart-header {
            display: grid;
            grid-template-columns: {{ config('filament-ecommerce.enable_pricing') ? '3fr 1fr 1fr 1fr 0.5fr' : '3fr 1fr 0.5fr' }};
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-color-light);
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .header-quantity, .header-price, .header-total {
            text-align: center;
        }

        .cart-item {
            display: grid;
            grid-template-columns: {{ config('filament-ecommerce.enable_pricing') ? '3fr 1fr 1fr 1fr 0.5fr' : '3fr 1fr 0.5fr' }};
            gap: 1rem;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-product {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .item-product img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-name {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .item-quantity, .item-price, .item-total {
            text-align: center;
        }

        .item-remove {
            text-align: right;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            text-align: center;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .btn-update {
            background: #e9ecef;
            border: none;
            color: var(--text-color);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .btn-remove {
            background: none;
            border: none;
            color: var(--text-color-light);
            font-size: 1.1rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .btn-remove:hover {
            color: #dc3545;
        }

        .cart-summary {
            width: 400px;
            background: var(--surface-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            position: sticky;
            top: 100px;
        }

        .cart-summary h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        .summary-row, .summary-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .summary-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-checkout {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 1rem;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn-continue-shopping, .btn-clear-cart {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-continue-shopping {
            border: 1px solid var(--border-color);
            color: var(--secondary-color);
        }

        .btn-continue-shopping:hover {
            background: var(--bg-color);
        }

        .btn-clear-cart {
            background: #f8d7da;
            color: #721c24;
            border: none;
            cursor: pointer;
        }

        .cart-empty {
            text-align: center;
            padding: 4rem 0;
        }

        .cart-empty i {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1.5rem;
        }

        .cart-empty p {
            font-size: 1.2rem;
            color: var(--text-color-light);
            margin-bottom: 2rem;
        }

        @media (max-width: 992px) {
            .cart-container {
                flex-direction: column;
            }

            .cart-summary {
                width: 100%;
                margin-top: 2rem;
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-header {
                display: none;
            }

            .cart-item {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }

            .item-product {
                flex-direction: column;
            }

            .item-quantity, .item-price, .item-total, .item-remove {
                text-align: center;
            }

            .quantity-form {
                justify-content: center;
            }
        }

    </style>
</div>
