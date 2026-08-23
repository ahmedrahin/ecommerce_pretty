<div class="card-product grid">
    <div class="card-product_wrapper">
        <a href="{{ route('product-details', $product->slug) }}" class="product-img">
            <img class="lazyload img-product"
                src="{{ asset($product->thumb_image ?? 'frontend/images/noimg.jpg') }}"
                data-src="{{ asset($product->thumb_image ?? 'frontend/images/noimg.jpg') }}"
                alt="{{ $product->name }}">
            @if ($product->back_image)
                <img class="lazyload img-hover"
                    src="{{ asset($product->back_image) }}"
                    data-src="{{ asset($product->back_image) }}">
            @endif
        </a>

        <livewire:frontend.shop.shop-product :productId="$product->id" />
        
        
        @if($product->badge ==  'trending')
            <ul class="product-badge_list">
                <li class="product-badge_item h6 trend">Trending</li>
            </ul>
        @elseif($product->badge ==  'flash')    
            <ul class="product-badge_list">
                <li class="product-badge_item flash-sale"><i class="icon icon-thunder"></i> Flash sale</li>
            </ul>
        @elseif($product->badge ==  'new')   
            <ul class="product-badge_list">
                <li class="product-badge_item h6 new">New arrival</li>
            </ul>
        @elseif($product->badge ==  'hot')    
            <ul class="product-badge_list">
                <li class="product-badge_item h6 hot">Hot</li>
            </ul>
        @endif
        
    </div>

    <div class="card-product_info">
        <a href="{{ route('product-details', $product->slug) }}" class="name-product h4 link">
            {{ Str::limit($product->name, 25, '...') }}
        </a>

        <div class="price-wrap">
            <span class="price-new h6">${{ $product->offer_price }}</span>
            @if ($product->discount_option != 1)
                <span class="price-old h6 fw-normal">${{ $product->base_price }}</span>
            @endif
        </div>
        @include('frontend.includes.rating')
    </div>
</div>