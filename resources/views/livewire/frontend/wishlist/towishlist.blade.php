<div>
    @if($isInWishlist)
        <span wire:click="$emit('removeFromWishlist', {{ $productId }})" style="color: var(--s-primary);"><i class="material-icons">bookmark</i> Unsave</span>
    @else
        <span wire:click="$emit('get_id', {{ $productId }})"><i class="material-icons">bookmark_border</i> Save</span>
    @endif
</div>
