@extends('layouts.app')

@section('title', 'Welcome to OMNIC Medical Store')

@section('content')
    <div class="welcome-page">

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-bg"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content container">
                <h1 data-aos="fade-down">Your Health, Our Priority</h1>
                <p data-aos="fade-up" data-aos-delay="200">Providing the highest quality medical equipment and
                    consumables to professionals and individuals.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" data-aos="zoom-in" data-aos-delay="400">Shop
                    All Products</a>
            </div>
        </section>

        <!-- Trending Products Section -->
        <section class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Trending Products</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Discover our most popular and highly-rated products, trusted by healthcare professionals worldwide.
                </p>
                @if($products->count() > 0)
                    <div class="product-grid">
                        @foreach($products as $product)
{{--                            @php(dd($product->getMedia('feature_image')->first()))--}}
                            <div class="product-card" data-aos="fade-up"
                                 data-aos-delay="{{ 100 + ($loop->iteration * 50) }}">
                                <a href="#" class="product-image-container">
                                    <img
                                        src="{{$product->getMedia('feature_image')->first()?->getUrl()??'https://placehold.co/600x400/007bff/white?text='. urlencode($product->name)}}"
                                        alt="{{ $product->name }}">
                                </a>
                                <div class="product-content">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <p class="product-description">{{ Str::limit($product->description, 80) ?: 'High-quality medical product.' }}</p>
                                    <div class="product-footer">
                                        {{--<span
                                            class="product-price">₦{{ number_format($product->price / 100, 2) }}</span>--}}
                                        <form action="{{ route('cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $product->id }}" name="id">
                                            <input type="hidden" value="{{ $product->name }}" name="name">
                                            <input type="hidden" value="{{ $product->price }}" name="price">
                                            <input type="hidden" value="1" name="quantity">
                                            <button type="submit" class="add-to-cart-btn" aria-label="Add to cart">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination-container">
                        {{ $products->links('pagination::default') }}
                    </div>
                @else
                    <p class="text-gray-500 col-span-full" style="text-align: center;">No trending products available at
                        the moment.</p>
                @endif

        </div>
    </section>
</div>
@endsection
