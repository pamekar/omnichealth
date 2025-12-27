<div>
    @if($large)
        <div class="add-to-cart-section" style="background: none; padding: 0;">
            <div class="quantity-control" style="margin-bottom: 1rem;">
                <label for="quantity">Quantity:</label>
                <input type="number" wire:model="quantity" id="quantity" min="1" class="form-input">
            </div>

            <button wire:click="addToCart" class="add-to-cart-btn-large">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
        </div>
    @else
        <button wire:click="addToCart" class="add-to-cart-btn" aria-label="Add to cart">
            <i class="fas fa-cart-plus"></i>
        </button>
    @endif
</div>