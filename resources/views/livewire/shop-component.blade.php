<div class="shop-page">
    <section class="section">
        <div class="container">
            <h2 class="section-title">Our Products</h2>
            <p class="section-subtitle">
                Browse our complete catalog of high-quality medical supplies and equipment.
            </p>

            <!-- Filters -->
            <div class="filters-wrapper" style="margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; justify-content: space-between;">
                <div class="search-container" style="flex: 1; min-width: 250px;">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div class="category-container" style="flex: 0 0 250px;">
                    <select wire:model.live="selectedCategory"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="loading-indicator" style="text-align: center; margin-bottom: 1rem; width: 100%;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>

            <div class="product-grid" wire:loading.class.opacity="0.5" style="transition: opacity 0.3s;">
                 @forelse ($products as $product)
                    <div class="product-card">
                        <a href="{{ route('shop.show', $product->slug) }}" class="product-image-container">
                            <img
                                src="{{$product->displayImage()??'https://placehold.co/600x400/007bff/white?text='. urlencode($product->name)}}"
                                alt="{{ $product->name }}">
                        </a>
                        <div class="product-content">
                            <h3 class="product-name">
                                <a href="{{ route('shop.show', $product->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="product-description">{!! Str::limit(strip_tags($product->description), 80) ?: 'High-quality medical product.' !!}</p>
                            <div class="product-footer">
                                <span class="product-price">₦{{ number_format($product->price, 2) }}</span>
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
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #666;">
                        <p>No products found matching your criteria.</p>
                    </div>
                @endforelse
            </div>
             <div class="pagination-container" style="margin-top: 2rem;">
                 {{ $products->links('pagination::default')  }}
            </div>
        </div>
    </section>
</div>
