@extends('layouts.app')

@section('title', $product->name . ' - OMNIC Medical Store')

@push('seo')
    <meta name="description" content="{{ $product->about ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="keywords" content="{{ $product->keywords ?? $product->name }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('shop.show', $product->slug) }}">
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ $product->about ?? Str::limit(strip_tags($product->description), 200) }}">
    <meta property="og:image" content="{{ $product->getMedia('feature_image')->first()?->getUrl() }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('shop.show', $product->slug) }}">
    <meta property="twitter:title" content="{{ $product->name }}">
    <meta property="twitter:description" content="{{ $product->about ?? Str::limit(strip_tags($product->description), 200) }}">
    <meta property="twitter:image" content="{{ $product->getMedia('feature_image')->first()?->getUrl() }}">

    <!-- Product Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "image": "{{ $product->getMedia('feature_image')->first()?->getUrl() }}",
        "description": "{{ $product->about ?? Str::limit(strip_tags($product->description), 200) }}",
        "sku": "{{ $product->sku }}",
        "offers": {
            "@type": "Offer",
            "url": "{{ route('shop.show', $product->slug) }}",
            "priceCurrency": "NGN",
            "price": "{{ $product->price }}",
            "priceValidUntil": "{{ now()->addYear()->toIso8601String() }}",
            "availability": "{{ $product->is_in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
        }
    }
    </script>
@endpush

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
                    @if($product->discount > 0)
                        <div class="price-wrapper">
                            <span class="original-price">₦{{ number_format($product->price, 2) }}</span>
                            <span class="price discounted">₦{{ number_format($product->price - ($product->price * ($product->discount / 100)), 2) }}</span>
                            <span class="discount-badge">-{{ $product->discount }}%</span>
                        </div>
                    @else
                        <span class="price">₦{{ number_format($product->price, 2) }}</span>
                    @endif

                    @if($product->is_in_stock)
                        <span class="stock-badge in-stock">In Stock</span>
                    @else
                        <span class="stock-badge out-of-stock">Out of Stock</span>
                    @endif
                </div>

                @if($product->about)
                <div class="product-about">
                    <p class="text-gray-600 italic mb-4">{{ $product->about }}</p>
                </div>
                @endif

                <div class="product-description">
                    {!! $product->description !!}
                </div>
                
                @if($product->details)
                <div class="product-details mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-bold mb-2">Product Details</h3>
                    <div class="prose prose-sm">
                        {!! $product->details !!}
                    </div>
                </div>
                @endif

                @if($product->is_in_stock)
                <div class="add-to-cart-section">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $product->id }}" name="id">
                        <input type="hidden" value="{{ $product->name }}" name="name">
                        <input type="hidden" value="{{ $product->price }}" name="price">
                        
                        <div class="quantity-control">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->max_cart > 0 ? $product->max_cart : 10 }}" class="form-input">
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
                    @if($product->barcode)
                        <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
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
        flex-wrap: wrap;
    }
    
    .price-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1.1rem;
    }
    
    .discount-badge {
        background: #ef4444;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .price {
        font-size: 1.5rem;
        font-weight: 600;
        color: #007bff;
    }
    
    .price.discounted {
        color: #dc2626;
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
