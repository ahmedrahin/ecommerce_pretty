<section class="related-product-list">
    <h3>You Might Also Like</h3>

    <div class="rp-slider-wrapper">
        <button class="rp-arrow rp-arrow-left" onclick="document.getElementById('rp-grid-scroll').scrollBy({left: -240, behavior: 'smooth'})" aria-label="Previous">
            <i class="bi bi-chevron-left"></i>
        </button>
        <div class="rp-grid" id="rp-grid-scroll">
            @foreach ($products->take(10) as $product)
                <div class="rp-card">
                    <a href="{{ route('product-details', $product->slug) }}" class="rp-img-wrap">
                        <img
                            src="{{ asset($product->thumb_image) }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                        >
                    </a>

                    <div class="rp-info">
                        <a href="{{ route('product-details', $product->slug) }}">
                            <p class="rp-name">{{ Str::limit($product->name, 30) }}</p>
                        </a>

                        <div class="rp-price">
                            <span class="rp-price-new">TK{{ format_price($product->offer_price) }}</span>
                            @if ($product->discount_option != 1)
                                <span class="rp-price-old">TK{{ format_price($product->base_price) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="rp-arrow rp-arrow-right" onclick="document.getElementById('rp-grid-scroll').scrollBy({left: 240, behavior: 'smooth'})" aria-label="Next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</section>