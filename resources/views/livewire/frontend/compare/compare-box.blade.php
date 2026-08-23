<div>
    <div class="f-btn cmpr-toggler" id="cmpr-btn">
        <i class="material-icons">library_add</i>
        <div class="label">Compare</div>
        <span class="counter">{{ $count }}</span>
    </div>

    <!-- Drawer -->
    <div class="drawer cmpr-panel" id="cmpr-panel">
        <div class="title">
            <p>Compare Product</p>
            <span class="cmpr-toggler"><i class="material-icons">close</i></span>
        </div>

        <div class="content">
            @forelse($products as $product)
                <div class="item">
                    <div class="info">
                        <div class="image">
                            <img src="{{ asset($product->thumb_image) }}" width="47" height="47">
                        </div>
                        <div class="name">{{ $product->name }}</div>
                    </div>
                    <span class="remove" wire:click="remove({{ $product->id }})" style="cursor:pointer">
                        <i class="material-icons" aria-hidden="true">delete</i>
                    </span>
                </div>
            @empty
                <div class="empty-content">
                    <p class="text-center">Your compare list is empty!</p>
                </div>
            @endforelse
        </div>

        @if($count > 0)
            <div class="footer btn-wrap">
                <a wire:click="clearAll" class="clear-all" style="cursor:pointer">Clear</a>
                <a class="btn" href="{{ route('compare', ['ids' => implode(',', $products->pluck('id')->toArray())]) }}">
                    Compare Now
                </a>
            </div>
        @endif
    </div>
</div>
