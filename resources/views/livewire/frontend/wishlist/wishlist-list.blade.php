<div>
    <div class="wishlist-container">
        @if (!$wishlistItems->isEmpty())
            <div class="wishlist-grid">
                @foreach ($wishlistItems as $item)
                    <div class="wishlist-card">
                        <div class="wishlist-image">
                            <a href="{{ route('product-details', $item->product->slug) }}">
                                <img src="{{ asset($item->product->thumb_image) }}" alt="{{ $item->product->name }}">
                            </a>
                            <button type="button" class="remove-btn" wire:click="removeFromWishlist({{ $item->id }})" title="Remove from wishlist">
                                <i class="bi bi-trash3"></i>
                            </button>
                            @if($item->product->quantity > 0 && $item->product->quantity <= 10)
                                <span class="stock-badge limited">Limited Stock</span>
                            @elseif($item->product->quantity <= 0)
                                <span class="stock-badge out">Out of Stock</span>
                            @endif
                        </div>
                        <div class="wishlist-info">
                            <h4 class="product-name">
                                <a href="{{ route('product-details', $item->product->slug) }}">
                                    {{ $item->product->name }}
                                </a>
                            </h4>
                            
                            <div class="product-price">
                                @if($item->product->offer_price && $item->product->offer_price != $item->product->base_price)
                                    <span class="price-new">৳{{ number_format($item->product->offer_price, 2) }}</span>
                                    <span class="price-old">৳{{ number_format($item->product->base_price, 2) }}</span>
                                @else
                                    <span class="price-new">৳{{ number_format($item->product->base_price, 2) }}</span>
                                @endif
                            </div>

                            <div class="stock-status">
                                @if ($item->product->productStock->count() < 1)
                                    @if ($item->product->quantity > 0)
                                        <span class="stock-badge in-stock">
                                            <i class="bi bi-check-circle-fill"></i> In Stock
                                        </span>
                                    @else
                                        <span class="stock-badge out">
                                            <i class="bi bi-x-circle-fill"></i> Out of Stock
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <div class="action-buttons">
                                @if($item->product->productStock->count() < 1)
                                    @if ($item->product->quantity > 0)
                                        <button type="button" class="add-to-cart-btn" wire:click="addToCart({{ $item->product->id }})">
                                            <span wire:loading.remove wire:target="addToCart({{ $item->product->id }})">
                                                <i class="bi bi-bag-plus"></i> Add to Cart
                                            </span>
                                            <span wire:loading wire:target="addToCart({{ $item->product->id }})" class="formloader"></span>
                                        </button>
                                    @else
                                        <button type="button" class="add-to-cart-btn disabled" disabled>
                                            <i class="bi bi-x-circle"></i> Out of Stock
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('product-details', $item->product->slug) }}" class="buy-now-btn">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-wishlist">
                <i class="bi bi-heart"></i>
                <h3>Your wishlist is empty</h3>
                <p>Start adding items you love to your wishlist!</p>
            </div>
        @endif
    </div>
</div>

<style>
    :root {
        --olive: #4a4a2e;
        --cream: #f5f2ec;
        --charcoal: #1c1c1c;
        --gold: #5d060e;
        --red: #c0392b;
        --green: #10b981;
        --yellow: #f59e0b;
        --light-gray: #e8e5df;
        --transition: all 0.3s ease;
    }

    .wishlist-container {
        margin-top: 20px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .wishlist-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(93, 6, 14, 0.12);
    }

    /* Image Section */
    .wishlist-image {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: var(--light-gray);
    }

    .wishlist-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .wishlist-card:hover .wishlist-image img {
        transform: scale(1.05);
    }

    /* Remove Button */
    .remove-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--red);
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 2;
    }

    .remove-btn:hover {
        background: var(--red);
        color: white;
        transform: scale(1.05);
    }

    .remove-btn i {
        font-size: 1.1rem;
    }

    /* Stock Badge on Image */
    .stock-badge {
        position: absolute;
        bottom: 12px;
        left: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
    }

    .stock-badge.limited {
        background: var(--yellow);
        color: white;
    }

    .stock-badge.out {
        background: var(--red);
        color: white;
    }

    /* Info Section */
    .wishlist-info {
        padding: 20px;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .product-name a {
        color: var(--charcoal);
        text-decoration: none;
        transition: var(--transition);
    }

    .product-name a:hover {
        color: var(--gold);
    }

    /* Price */
    .product-price {
        margin-bottom: 12px;
    }

    .price-new {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--gold);
    }

    .price-old {
        font-size: 0.85rem;
        font-weight: 400;
        color: #9ca3af;
        text-decoration: line-through;
        margin-left: 8px;
    }

    /* Stock Status */
    .stock-status {
        margin-bottom: 16px;
    }

    .stock-badge.in-stock {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #d1fae5;
        color: var(--green);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .stock-badge.in-stock i {
        font-size: 0.7rem;
    }

    .stock-badge.out {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fee2e2;
        color: var(--red);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .stock-badge.out i {
        font-size: 0.7rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .add-to-cart-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        background: var(--gold);
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--transition);
        cursor: pointer;
    }

    .add-to-cart-btn:hover:not(.disabled) {
        background: var(--olive);
        transform: translateY(-2px);
    }

    .add-to-cart-btn.disabled {
        background: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .buy-now-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        background: white;
        color: var(--gold);
        text-decoration: none;
        border: 1px solid var(--gold);
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .buy-now-btn:hover {
        background: var(--gold);
        color: white;
        transform: translateY(-2px);
    }

    /* Form Loader */
    .formloader {
        width: 18px;
        height: 18px;
        border: 2px solid white;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-wishlist {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 24px;
    }

    .empty-wishlist i {
        font-size: 4rem;
        color: var(--light-gray);
        margin-bottom: 16px;
    }

    .empty-wishlist h3 {
        font-size: 1.2rem;
        color: var(--charcoal);
        margin-bottom: 8px;
    }

    .empty-wishlist p {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .shop-now-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding: 12px 28px;
        background: var(--gold);
        color: white;
        text-decoration: none;
        border-radius: 40px;
        font-weight: 500;
        transition: var(--transition);
    }

    .shop-now-btn:hover {
        background: var(--olive);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .wishlist-image {
            height: 200px;
        }

        .wishlist-info {
            padding: 16px;
        }

        .price-new {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .wishlist-grid {
            grid-template-columns: 1fr;
        }
    }
</style>