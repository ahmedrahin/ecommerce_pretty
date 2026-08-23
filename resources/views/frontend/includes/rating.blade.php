<div class="rating">
    @php
        $averageRating = round($product->reviews->avg('rating'), 1);
    @endphp
    <div class="d-flex gap-4">
        @for ($i = 1; $i <= 5; $i++) @php $isFull=$averageRating>= $i;
            $isHalf = !$isFull && $averageRating > ($i - 1) && $averageRating < $i; @endphp @if ($isFull) <svg
                width="14" height="14" viewBox="0 0 14 14" fill="#EF9122" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z" />
                </svg>
        @elseif ($isHalf)
                <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="half-star-{{ $i }}">
                            <stop offset="50%" stop-color="#EF9122" />
                            <stop offset="50%" stop-color="#ccc" />
                        </linearGradient>
                    </defs>
                    <path
                        d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                        fill="url(#half-star-{{ $i }})" />
                </svg>
            @else
                <svg width="14" height="14" viewBox="0 0 14 14" fill="#ccc" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z" />
                </svg>
            @endif
        @endfor
    </div>
</div>