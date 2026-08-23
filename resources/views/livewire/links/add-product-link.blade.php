<div>
    <button wire:click="$emit('redirectTo')" class="btn btn-primary"  @click.prevent="navigate">
        {!! getIcon('plus', 'fs-2', '', 'i') !!}
        Add Product
    </button>
</div>
