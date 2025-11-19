@extends('layouts.app')

@section('title', 'Shop - OMNIC Medical Store')

@section('content')
<div class="shop-page">

    <section class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Products</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                Browse our complete catalog of high-quality medical supplies and equipment.
            </p>
            <div class="product-grid">
                 @forelse ($products as $product)
                    <div class="product-card" data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->iteration * 50) }}">
                        <a href="#" class="product-image-container">
                            <img src="https://placehold.co/600x400/007bff/white?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-content">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-description">{{ Str::limit($product->description, 80) ?: 'High-quality medical product.' }}</p>
                            <div class="product-footer">
                                <span class="product-price">${{ number_format($product->price / 100, 2) }}</span>
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
                @empty
                    <p class="text-center text-gray-500 col-span-full">No products available at the moment.</p>
                @endforelse
            </div>
             <div class="pagination-container">
                 {{ $products->links('pagination::default') }}
            </div>
        </div>
    </section>

</div>
@endsection