<div>
    @php
        $ratingPercentage = $averageRating ? ($averageRating / 5) * 100 : 0;
    @endphp

    <div class="ratings-container">
        <div class="ratings-full">
            <span class="ratings" style="width: {{ $ratingPercentage }}%;"></span>
            <span class="tooltiptext tooltip-top">{{ number_format($averageRating, 1) }} out of 5</span>
        </div>
        <a href="#product-tab-reviews" class="rating-reviews">
            ({{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }})
        </a>
    </div>

</div>