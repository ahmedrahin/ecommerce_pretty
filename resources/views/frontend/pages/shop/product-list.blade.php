
@if (!$products->isEmpty())
    
    @foreach ($products as $product)
        @include('frontend.includes.product-info')
    @endforeach

    {{-- Pagination --}}
    <div class="wd-full wg-pagination mb-4 justify-content-center">

        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <span class="pagination-item h6 direct disabled">
                <i class="icon icon-caret-left"></i>
            </span>
        @else
            <a href="{{ $products->previousPageUrl() }}" class="pagination-item h6 direct">
                <i class="icon icon-caret-left"></i>
            </a>
        @endif

        {{-- Pagination Numbers --}}
        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if ($page == $products->currentPage())
                <span class="pagination-item h6 active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="pagination-item h6">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="pagination-item h6 direct">
                <i class="icon icon-caret-right"></i>
            </a>
        @else
            <span class="pagination-item h6 direct disabled">
                <i class="icon icon-caret-right"></i>
            </span>
        @endif

    </div>
    
@else
    <div class="empty-content text-center pt-md-5">
        <h5 class="text-danger">Sorry! No Product Found</h5>
        <p>Please try searching for something else</p>
    </div>
@endif

