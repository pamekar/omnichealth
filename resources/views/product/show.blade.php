@extends('layouts.app')

@section('title', $product->name . ' - OMNIC Medical Store')

@section('content')
<div class="product-detail-page section">
    <div class="container">
        <div class="product-detail-grid">
            <div class="product-image-gallery" data-aos="fade-right">
                <div class="main-image">
                    <img src="{{ $product->getMedia('feature_image')->first()?->getUrl() ?? 'https://placehold.co/600x600/007bff/white?text=' . urlencode($product->name) }}" alt="{{ $product->name }}">
                </div>
            </div>

            <div class="product-info" data-aos="fade-left">
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-price-container">
                    <span class="price">₦{{ number_format($product->price, 2) }}</span>
                    @if($product->is_in_stock)
                        <span class="stock-badge in-stock">In Stock</span>
                    @else
                        <span class="stock-badge out-of-stock">Out of Stock</span>
                    @endif
                </div>

                <div class="product-description">
                    {!! $product->description !!}
                </div>

                @if($product->is_in_stock)
                <div class="add-to-cart-section">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $product->id }}" name="id">
                        <input type="hidden" value="{{ $product->name }}" name="name">
                        <input type="hidden" value="{{ $product->price }}" name="price">
                        
                        <div class="quantity-control">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="form-input">
                        </div>

                        <button type="submit" class="add-to-cart-btn-large">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
                @endif
                
                <div class="product-meta">
                    @if($product->category)
                        <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    @endif
                    @if($product->sku)
                        <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-top: 2rem;
    }

    .main-image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .product-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .product-price-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .price {
        font-size: 1.5rem;
        font-weight: 600;
        color: #007bff;
    }

    .stock-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stock-badge.in-stock {
        background-color: #d1fae5;
        color: #065f46;
    }

    .stock-badge.out-of-stock {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .product-description {
        margin-bottom: 2rem;
        line-height: 1.6;
        color: #555;
    }

    .add-to-cart-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .quantity-control {
        margin-bottom: 1rem;
    }

    .quantity-control label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-input {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 80px;
    }

    .add-to-cart-btn-large {
        width: 100%;
        padding: 0.75rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .add-to-cart-btn-large:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .product-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
