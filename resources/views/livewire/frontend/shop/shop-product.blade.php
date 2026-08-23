<div class="actions">
    @if($product->productStock->count() > 0)
        <a class="btn-options" href="{{ route('product-details', $product->slug) }}">
            <i class="bi bi-sliders2"></i> Select options
        </a>
    @else
        @if($product->stock_out == 1 || $product->quantity == 0)
            <button class="btn-outofstock" type="button" disabled
                onclick="message('error', 'Product is not available!')">
                <i class="bi bi-x-circle-fill"></i> Out of stock
            </button>
        @else
            <button class="btn-cart" type="button" wire:click="addToCart">
                <i class="bi bi-cart-plus"></i> Add to cart
            </button>
        @endif
    @endif
</div>