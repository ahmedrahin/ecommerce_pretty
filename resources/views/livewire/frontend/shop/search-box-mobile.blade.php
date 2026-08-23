<div class="mobile-search">
    <!-- Search Box (Hidden by Default) -->
    <div class="search-box" id="mobileSearchBox" style="display: none;">
        <form wire:submit.prevent="search">
            <input
                type="text"
                wire:model="searchQuery"
                placeholder="Search products by title or tags"
            />
            <i class="fa fa-search" aria-hidden="true"></i>
            <button type="submit" style="display: none;">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </form>

        @if(!empty($searchQuery))
            <div class="product-list theme-scrollbar" id="product-list">
                <ul class="list-unstyled m-0 p-2">
                    @forelse($products as $product)
                        <li class="py-1 {{ $loop->last ? 'last' : '' }}">
                            <a href="{{ route('product-details', $product['slug']) }}" class="text-dark text-decoration-none">
                                <div class="product-img">
                                    <img src="{{ asset($product['thumb_image']) }}" alt="">
                                </div>
                                <div class="product-info">
                                    {{ $product['name'] }}
                                    <span>
                                        {{ $product['offer_price'] }}৳
                                        @if($product['discount_option'] != 1)
                                            <del class="text-danger opacity-80">
                                                {{ $product['base_price'] }}৳
                                            </del>
                                        @endif
                                    </span>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="py-1 text-muted last no-found">No product found</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
</div>

