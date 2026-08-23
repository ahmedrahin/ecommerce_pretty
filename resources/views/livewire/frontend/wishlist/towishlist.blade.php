<div>
    @if($isInWishlist)
        <button type="button" class="hover-tooltip box-icon btn-add-wishlist" wire:click="$emit('removeFromWishlist', {{ $productId }})">
            <span class="icon icon-trash"></span>
            <span class="tooltip">Remove Wishlist</span>
        </button>
    @else
        <button type="button" class="hover-tooltip box-icon btn-add-wishlist" wire:click="$emit('get_id', {{ $productId }})">
            <span class="icon icon-heart"></span>
            <span class="tooltip">Add to Wishlist</span>
        </button>
    @endif
</div>
