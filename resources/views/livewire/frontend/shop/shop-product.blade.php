<ul class="product-action_list">
    @if($product->productStock->count() > 0)
        <li>
            <a href="{{ route('product-details', $product->slug) }}" class="hover-tooltip tooltip-left box-icon">
                <span class="icon icon-shopping-cart-simple"></span>
                <span class="tooltip">Buy Now</span>
            </a>
        </li>
    @else
        @if($product->quantity > 0)
            <li>
                <a href="javascript:void(0);" 
                   class="hover-tooltip tooltip-left box-icon" 
                   wire:click="addToCart">
                    <span class="icon icon-shopping-cart-simple"></span>
                    <span class="tooltip">Add to Cart</span>
                </a>
            </li>
        @else
            <li>
                <a href="javascript:void(0);" class="hover-tooltip tooltip-left box-icon disabled" onclick="message('error', 'Product is not available!')"">
                    <span class="bi-exclamation-triangle"></span>
                    <span class="tooltip">Out of Stock</span>
                </a>
            </li>
        @endif
    @endif
    
    <li>
        <a href="javascript:void(0);" 
           class="hover-tooltip tooltip-left box-icon" 
           wire:click="toggleWishlist({{ $productId }})">
            @if($isInWishlist)
                <span class="icon icon-trash" style="color:#111;"></span>
                <span class="tooltip">Remove Wishlist</span>
            @else
                <span class="icon icon-heart"></span>
                <span class="tooltip">Add to Wishlist</span>
            @endif
        </a>
    </li>
</ul>