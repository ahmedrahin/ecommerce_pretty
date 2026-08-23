<div class="modal modalCentered fade modal-search" id="search" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            <div>
                <form class="form-search style-2" wire:submit.prevent="performSearch">
                    <fieldset>
                        <input type="text" 
                               placeholder="Search products..." 
                               class="style-stroke" 
                               wire:model.debounce.300ms="searchQuery"
                               autocomplete="off"
                               required>
                    </fieldset>
                    <button type="submit" class="link">
                        <i class="icon icon-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            @if(!empty($searchQuery))
                <div class="account-order_tab">
                    <ul class="tab-order_detail" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#order-history" 
                               data-bs-toggle="tab" 
                               class="tf-btn-line tf-btn-tab {{ $activeTab === 'products' ? 'active' : '' }}"
                               aria-selected="false" 
                               role="tab" 
                               tabindex="-1"
                               wire:click="setActiveTab('products')">
                                <span class="h4">
                                    Products ({{ count($products) }})
                                </span>
                            </a>
                        </li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#item-detail" 
                               data-bs-toggle="tab" 
                               class="tf-btn-line tf-btn-tab {{ $activeTab === 'categories' ? 'active' : '' }}"
                               aria-selected="false" 
                               role="tab" 
                               tabindex="-1"
                               wire:click="setActiveTab('categories')">
                                <span class="h4">
                                    Categories ({{ count($filteredCategories) }})
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content overflow-hidden">
                        <div class="tab-pane view-history-wrap {{ $activeTab === 'products' ? 'active show' : '' }}" id="order-history" role="tabpanel">
                            <div class="view-history-list">
                                @forelse($products as $product)
                                    <a class="item text-main link h6" 
                                       href="{{ route('product-details', $product['slug']) }}"
                                       style="border-bottom: 1px solid #5f615e1c;padding-bottom: 10px;"
                                       >
                                       <div>
                                            <span>{{ $product['name'] }}</span>
                                            <div class="price-info" style="font-size: 14px;">
                                                ${{ format_price($product['offer_price']) }}
                                                @if (isset($product['discount_option']) && $product['discount_option'] != 1)
                                                    <del class="text-danger">${{ format_price($product['base_price']) }}</del>
                                                @endif
                                            </div>
                                       </div>
                                        <i class="icon icon-arrow-top-right"></i>
                                    </a>
                                @empty
                                    <div class="no-results">No products found</div>
                                @endforelse

                                @if(count($products) > 6)
                                    <a class="item text-main link h6 see-all" 
                                       href="{{ route('search.products', ['query' => $searchQuery]) }}">
                                        <span>See all results</span>
                                        <i class="icon icon-arrow-top-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane view-history-wrap {{ $activeTab === 'categories' ? 'active show' : '' }}" id="item-detail" role="tabpanel">
                            <div class="view-history-list ">
                                @forelse($filteredCategories as $category)
                                    <a class="item text-main link h6" 
                                       href="{{ route('category.products', $category->slug) }}">
                                        <div>
                                            <span>{{ $category->name }}</span>
                                            <div class="product-count" style="font-size: 14px;">{{ $category->product_count }} products</div>
                                        </div>
                                        <i class="icon icon-arrow-top-right"></i>
                                    </a>
                                @empty
                                    <div class="no-results">No categories found</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Popular searches when no query --}}
                <div class="view-history-wrap">
                    <h4 class="title">Top Categories</h4>
                    <div class="view-history-list">
                        @foreach ($topCategories as $category)
                            <a class="item text-main link h6" href="">
                                <span>{{ $category->name }}</span>
                                <i class="icon icon-arrow-top-right"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>