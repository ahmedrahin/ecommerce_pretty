<section class="review  q-n-r-section bg-white m-tb-15" id="write-review">
    <div class="section-head">
        <div class="title-n-action">
            <h2>Reviews ({{ $totalReviews }})</h2>
            <p class="section-blurb">Get specific details about this product from customers who own it.</p>
            @if ($totalReviews > 0)
                <div class="average-rating">
                    <span class="count">
                        <b>{{ round($averageRating, 1) }}</b>
                        <span> out of 5</span>
                    </span>
                    <span class="rating">
                        @php
                            $fullStars = floor($averageRating);
                            $halfStar = $averageRating - $fullStars >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp

                        @for ($i = 0; $i < $fullStars; $i++)
                            <span class="material-icons">star</span>
                        @endfor

                        @if ($halfStar)
                            <span class="material-icons">star_half</span>
                        @endif

                        @for ($i = 0; $i < $emptyStars; $i++)
                            <span class="material-icons">star_border</span>
                        @endfor
                    </span>
                </div>

            @endif
        </div>
        <div class="q-action">
            @if (Auth::check() && $userReview)
                <button class="btn st-outline"
                    onclick="message('info', 'You already submitted a review for this product.')">Submitted</button>
            @else
                @if (config('website_settings.allow_guest_reviews') || Auth::check())
                    <button class="btn st-outline" data-bs-toggle="modal" data-bs-target="#Reviews-modal"
                        title="Write your review" tabindex="0">Write a Review</button>
                @elseif(!Auth::check())
                    <button class="btn st-outline"
                        onclick="message('warning', 'Login at first to write a review')">Write a
                        Review</button>
                @endif
            @endif
        </div>
    </div>

    <div id="review">
        @if ($totalReviews < 1)
            <div class="empty-content">
                <span class="icon material-icons">assignment</span>
                <div class="empty-text">This product has no reviews yet. Be the first one to write a 
                    review.</div>
            </div>
        @else
            <div id="review">
                @foreach ($reviews as $review)
                    <div class="review-wrap">
                        <div class="review-author">
                            <span class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <span class="material-icons">star</span>
                                    @else
                                        <span class="material-icons">star_border</span>
                                    @endif
                                @endfor
                            </span>
                        </div>
                        <p class="review">
                            {{ $review->comment }}
                        </p>
                        <p class="author">
                            By <span class="name">
                                {{ $review->user_id && optional($review->user)->name ? $review->user->name : $review->name }}
                            </span>

                            on {{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}
                        </p>
                    </div>
                @endforeach
            </div>

             @if ($totalReviews > 3)
                <div class="text-right">
                    <ul class="pagination">
                        {{-- Prev --}}
                        @if ($reviews->onFirstPage())
                            <li><span class="disabled">PREV</span></li>
                        @else
                            <li><a href="#" wire:click.prevent="previousPage">PREV</a></li>
                        @endif

                        {{-- Pages --}}
                        @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                            @if ($page == $reviews->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="#"
                                        wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($reviews->hasMorePages())
                            <li><a href="#" wire:click.prevent="nextPage">NEXT</a></li>
                        @else
                            <li><span class="disabled">NEXT</span></li>
                        @endif
                    </ul>
                </div>
            @endif
        @endif
    </div>

    @if (config('website_settings.allow_guest_reviews') || Auth::check())
        <div class="customer-reviews-modal modal theme-modal fade" id="Reviews-modal" tabindex="-1" role="dialog"
            aria-modal="true" wire:ignore.self>
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="color: black;">Write A Review</h3>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa fa-times" aria-hidden="true"></i></button>
                    </div>

                    <div class="modal-body pt-0">

                        <form wire:submit.prevent="submit">
                            <div class="">
                                <div class="col-12">
                                    <div class="reviews-product">
                                        <div>
                                            <img src="{{ asset($product->thumb_image) }}" alt="">
                                        </div>
                                        <div>
                                            <h5>{{ $product->name }}</h5>
                                            <p style="font-weight: 600;">
                                                {{ $product->offer_price }}৳
                                                @if ($product->discount_option == 2 || $product->discount_option == 3)
                                                    <del class="text-danger">{{ $product->base_price }}৳</del>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="customer-rating">
                                        <label class="form-label">Rating :</label>

                                        <div class="selectrating" id="star-rating">
                                            <input type="radio" name="rating" id="r5" value="5"
                                                wire:model="rating">
                                            <label for="r5" onclick="setRating(5)"></label>

                                            <input type="radio" name="rating" id="r4" value="4"
                                                wire:model="rating">
                                            <label for="r4" onclick="setRating(4)"></label>

                                            <input type="radio" name="rating" id="r3" value="3"
                                                wire:model="rating">
                                            <label for="r3" onclick="setRating(3)"></label>

                                            <input type="radio" name="rating" id="r2" value="2"
                                                wire:model="rating">
                                            <label for="r2" onclick="setRating(2)"></label>

                                            <input type="radio" name="rating" id="r1" value="1"
                                                wire:model="rating">
                                            <label for="r1" onclick="setRating(1)"></label>
                                        </div>
                                        @error('rating')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>

                                @guest
                                    <div class="row g-3" style="margin-bottom:15px;">
                                        <div class="col-md-6">
                                            <div class="from-group">
                                                <label class="form-label">Name :</label>
                                                <input type="text"
                                                    class="form-control @error('name') error-border @enderror"
                                                    name="name" placeholder="Your name" wire:model="name">
                                                @error('name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="from-group">
                                                <label class="form-label">Email :</label>
                                                <input type="text"
                                                    class="form-control @error('email') error-border @enderror"
                                                    name="name" placeholder="Enter email" wire:model="email">
                                                @error('email')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endguest

                                <div class="col-12 mt-2">
                                    <div class="from-group">
                                        <label class="form-label">Review Content :</label>
                                        <textarea class="form-control @error('comment') error-border @enderror" id="comment" cols="30" rows="4"
                                            placeholder="Write your comments here..." wire:model="comment"></textarea>
                                        @error('comment')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-button-group">
                                    <button class="btn btn-cancel cancel-modal-review" type="button"
                                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button class="btn btn-submit" type="submit" style="width: 160px;">
                                        <span wire:loading.remove wire:target="submit">Leave a review</span>
                                        <span wire:loading wire:target="submit" class="formloader"></span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    @endif

</section>
