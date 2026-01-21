<div x-data="{ added: false }">
    @if($large)
        <div class="add-to-cart-section" style="background: none; padding: 0;">
            <div class="quantity-control" style="margin-bottom: 1rem;">
                <label for="quantity">Quantity:</label>
                <input type="number" wire:model="quantity" id="quantity" min="1" class="form-input">
            </div>

            <button @click="$wire.addToCart().then(() => { added = true; setTimeout(() => added = false, 2000) })" 
                    wire:loading.attr="disabled"
                    class="add-to-cart-btn-large relative overflow-hidden transition-all duration-300"
                    :class="{ 'bg-green-600 hover:bg-green-700': added }">
                
                <!-- Default State -->
                <span wire:loading.remove x-show="!added" class="flex items-center justify-center gap-2">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </span>

                <!-- Loading State -->
                <span wire:loading class="flex items-center justify-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i> Adding...
                </span>

                <!-- Success State -->
                <span x-show="added" x-cloak class="flex items-center justify-center gap-2" 
                      x-transition:enter="transition ease-out duration-300"
                      x-transition:enter-start="opacity-0 transform scale-90"
                      x-transition:enter-end="opacity-100 transform scale-100"
                      x-transition:leave="transition ease-in duration-300"
                      x-transition:leave-start="opacity-100 transform scale-100"
                      x-transition:leave-end="opacity-0 transform scale-90">
                    <i class="fas fa-check"></i> Added
                </span>
            </button>
        </div>
    @else
        <button @click="$wire.addToCart().then(() => { added = true; setTimeout(() => added = false, 2000) })" 
                wire:loading.attr="disabled"
                class="add-to-cart-btn relative transition-all duration-300"
                :class="{ '!bg-green-600 hover:!bg-green-700': added }"
                aria-label="Add to cart">
                
            <!-- Default State -->
            <span wire:loading.remove x-show="!added">
                <i class="fas fa-cart-plus"></i>
            </span>

            <!-- Loading State -->
            <span wire:loading>
                <i class="fas fa-spinner fa-spin"></i>
            </span>

            <!-- Success State -->
            <span x-show="added" x-cloak
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 transform scale-90"
                  x-transition:enter-end="opacity-100 transform scale-100"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 transform scale-100"
                  x-transition:leave-end="opacity-0 transform scale-90">
                <i class="fas fa-check"></i>
            </span>
        </button>
    @endif
</div>
