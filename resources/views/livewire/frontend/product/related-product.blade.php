 <section class="related-product-list">
    <h3>Related Product</h3>
    @foreach ($products->take(10) as $product)
        <div class="p-s-item">
            <div class="image-holder">
                <a href="{{ route('product-details',$product->slug) }}">
                    <img src="{{ asset($product->thumb_image) }}"
                        alt="{{ $product->name }}"
                        width="80" height="80"></a>
            </div>
            <div class="caption">
                <h4 class="product-name">
                    <a
                        href="{{ route('product-details',$product->slug) }}">Lenovo
                        {{ $product->name }}
                    </a>
                </h4>
                <div class="p-item-price price">
                    <span class="price-new">{{ format_price($product->offer_price) }}৳</span>
                    @if ($product->discount_option != 1)
                        <span class="price-old">{{ format_price($product->base_price) }}৳</span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</section>
