<div>
    <section class="flat-spacing-3" style="padding-top: 0px;padding-bottom:50px;" wire:ignore.self>
        <div class="container">
            <div class="flat-animate-tab tab-style-1" wire:ignore.self>
                <ul class="menu-tab menu-tab-1" role="tablist">
                    <li class="nav-tab-item" role="presentation">
                        <a href="#descriptions" class="tab-link active" data-bs-toggle="tab">Descriptions</a>
                    </li>

                    <li class="nav-tab-item" role="presentation">
                        <a href="#reviews" id="reviewTabBtn" class="tab-link" data-bs-toggle="tab">Customer Reviews</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane wd-product-descriptions active show" id="descriptions" role="tabpanel">
                        <div class="tab-descriptions">
                            @if (!is_null($product->long_description) && $product->long_description != '<p><br></p>')
                            {!! $product->long_description !!}
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane wd-product-descriptions" id="reviews" role="tabpanel">
                        <div class="tab-reviews write-cancel-review-wrap" wire:ignore.self>
                            <div class="tab-reviews-heading">
                                <div class="top">
                                    <div class="text-center">
                                        <div class="number fw-6 justify-content-center">{{ $totalReviews }}</div>
                                        @php
                                        $fullStars = floor($averageRating);
                                        $halfStar = $averageRating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        @endphp

                                        <div class="list-star d-flex justify-content-center gap-2">
                                            @for ($i = 0; $i < $fullStars; $i++) <svg width="14" height="14"
                                                viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                    fill="#EF9122"></path>
                                                </svg>
                                                @endfor

                                                @if ($halfStar)
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <defs>
                                                        <linearGradient id="halfStarGradient">
                                                            <stop offset="50%" stop-color="#EF9122" />
                                                            <stop offset="50%" stop-color="#ddd" />
                                                        </linearGradient>
                                                    </defs>
                                                    <path
                                                        d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                        fill="url(#halfStarGradient)"></path>
                                                </svg>
                                                @endif

                                                @for ($i = 0; $i < $emptyStars; $i++) <svg width="14" height="14"
                                                    viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                        fill="#ddd"></path>
                                                    </svg>
                                                    @endfor
                                        </div>

                                        <p class="quantity-reviews">
                                            Average {{ round($averageRating, 1) }} / 5 ({{ $totalReviews }} reviews)
                                        </p>
                                    </div>

                                    <div class="rating-score">
                                        @foreach ($ratingBreakdown as $stars => $percentage)
                                        <div class="item">
                                            <div class="number-1">{{ $stars }}</div>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                    fill="#EF9122" />
                                            </svg>
                                            <div class="line-bg">
                                                <div style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <div class="number-2">{{ $percentage }}%</div>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>

                                <div class="btns-reviews">
                                    @if (Auth::check() && $userReview)
                                    <button type="button" class="tf-btn animate-btn animate-dark line"
                                        onclick="message('info', 'You already submitted a review for this product.')">
                                        Submitted
                                    </button>
                                    @else
                                    @if (config('website_settings.allow_guest_reviews') || Auth::check())
                                    <button type="button" class="tf-btn animate-btn animate-dark line"
                                        data-bs-toggle="modal" data-bs-target="#ReviewsModal">
                                        Write a review
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.9834 5.15866L12.8412 2.0171C12.7367 1.9126 12.6127 1.82971 12.4762 1.77316C12.3397 1.71661 12.1933 1.6875 12.0456 1.6875C11.8978 1.6875 11.7515 1.71661 11.615 1.77316C11.4785 1.82971 11.3545 1.9126 11.25 2.0171L2.57977 10.6873C2.47485 10.7914 2.39167 10.9153 2.33506 11.0518C2.27844 11.1884 2.24953 11.3348 2.25001 11.4826V14.6248C2.25001 14.9232 2.36853 15.2093 2.57951 15.4203C2.79049 15.6313 3.07664 15.7498 3.37501 15.7498H15.1875C15.3367 15.7498 15.4798 15.6906 15.5853 15.5851C15.6907 15.4796 15.75 15.3365 15.75 15.1873C15.75 15.0381 15.6907 14.8951 15.5853 14.7896C15.4798 14.6841 15.3367 14.6248 15.1875 14.6248H8.10844L15.9834 6.74983C16.0879 6.64536 16.1708 6.52133 16.2274 6.38482C16.2839 6.24831 16.313 6.102 16.313 5.95424C16.313 5.80649 16.2839 5.66017 16.2274 5.52367C16.1708 5.38716 16.0879 5.26313 15.9834 5.15866ZM6.51727 14.6248H3.37501V11.4826L9.56251 5.29506L12.7048 8.43733L6.51727 14.6248ZM13.5 7.6421L10.3584 4.49983L12.0459 2.81233L15.1875 5.9546L13.5 7.6421Z"
                                                fill="white" />
                                        </svg>
                                    </button>
                                    @else
                                    <button type="button" class="tf-btn animate-btn animate-dark line"
                                        onclick="message('warning', 'Login at first to write a review')">
                                        Write a review
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.9834 5.15866L12.8412 2.0171C12.7367 1.9126 12.6127 1.82971 12.4762 1.77316C12.3397 1.71661 12.1933 1.6875 12.0456 1.6875C11.8978 1.6875 11.7515 1.71661 11.615 1.77316C11.4785 1.82971 11.3545 1.9126 11.25 2.0171L2.57977 10.6873C2.47485 10.7914 2.39167 10.9153 2.33506 11.0518C2.27844 11.1884 2.24953 11.3348 2.25001 11.4826V14.6248C2.25001 14.9232 2.36853 15.2093 2.57951 15.4203C2.79049 15.6313 3.07664 15.7498 3.37501 15.7498H15.1875C15.3367 15.7498 15.4798 15.6906 15.5853 15.5851C15.6907 15.4796 15.75 15.3365 15.75 15.1873C15.75 15.0381 15.6907 14.8951 15.5853 14.7896C15.4798 14.6841 15.3367 14.6248 15.1875 14.6248H8.10844L15.9834 6.74983C16.0879 6.64536 16.1708 6.52133 16.2274 6.38482C16.2839 6.24831 16.313 6.102 16.313 5.95424C16.313 5.80649 16.2839 5.66017 16.2274 5.52367C16.1708 5.38716 16.0879 5.26313 15.9834 5.15866ZM6.51727 14.6248H3.37501V11.4826L9.56251 5.29506L12.7048 8.43733L6.51727 14.6248ZM13.5 7.6421L10.3584 4.49983L12.0459 2.81233L15.1875 5.9546L13.5 7.6421Z"
                                                fill="white" />
                                        </svg>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <div id="review">
                                @if ($totalReviews < 1) <div class="empty-content">
                                    <div class="text-danger text-center" style="font-weight: 500;">
                                        This product has no reviews yet. Be the first one to write a review.
                                    </div>
                            </div>
                            @else
                                <div class="reply-comment cancel-review-wrap">
                                    <div class="reply-comment-wrap">
                                        @foreach ($reviews as $review)
                                        <div class="reply-comment-item mb-4">
                                            <div class="image">
                                                <img class="lazyload"
                                                    data-src="{{ $review->user && $review->user->avatar ? asset($review->user->avatar) : asset('frontend/images/user.png') }}"
                                                    src="{{ $review->user && $review->user->avatar ? asset($review->user->avatar) : asset('frontend/images/user.png') }}">
                                            </div>

                                            <div>
                                                <div class="user">
                                                    <div class="flex-grow-1">
                                                        <h4 class="name">
                                                            <a href="#" class="link">
                                                                {{ $review->user_id && optional($review->user)->name ?
                                                                $review->user->name :
                                                                $review->name }}
                                                            </a>
                                                        </h4>
                                                        <div class="user-infor">
                                                            <div class="color">
                                                                {{ \Carbon\Carbon::parse($review->created_at)->format('F j, Y')}}
                                                            </div>
                                                            <div class="line"></div>

                                                            @php
                                                                $verified = \App\Models\OrderItems::whereHas('order', function ($q)
                                                                use ($review) {
                                                                $q->where('user_id', $review->user_id);
                                                                })
                                                                ->where('product_id', $review->product_id)
                                                                ->exists();
                                                            @endphp

                                                            @if ($verified)
                                                            <div class="verified-purchase d-flex align-items-center gap-1">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M17.6453 8.03281C17.3508 7.725 17.0461 7.40781 16.9312 7.12891C16.825 6.87344 16.8187 6.45 16.8125 6.03984C16.8008 5.27734 16.7883 4.41328 16.1875 3.8125C15.5867 3.21172 14.7227 3.19922 13.9602 3.1875C13.55 3.18125 13.1266 3.175 12.8711 3.06875C12.593 2.95391 12.275 2.64922 11.9672 2.35469C11.4281 1.83672 10.8156 1.25 10 1.25C9.18437 1.25 8.57266 1.83672 8.03281 2.35469C7.725 2.64922 7.40781 2.95391 7.12891 3.06875C6.875 3.175 6.45 3.18125 6.03984 3.1875C5.27734 3.19922 4.41328 3.21172 3.8125 3.8125C3.21172 4.41328 3.20312 5.27734 3.1875 6.03984C3.18125 6.45 3.175 6.87344 3.06875 7.12891C2.95391 7.40703 2.64922 7.725 2.35469 8.03281C1.83672 8.57188 1.25 9.18437 1.25 10C1.25 10.8156 1.83672 11.4273 2.35469 11.9672C2.64922 12.275 2.95391 12.5922 3.06875 12.8711C3.175 13.1266 3.18125 13.55 3.1875 13.9602C3.19922 14.7227 3.21172 15.5867 3.8125 16.1875C4.41328 16.7883 5.27734 16.8008 6.03984 16.8125C6.45 16.8187 6.87344 16.825 7.12891 16.9312C7.40703 17.0461 7.725 17.3508 8.03281 17.6453C8.57188 18.1633 9.18437 18.75 10 18.75C10.8156 18.75 11.4273 18.1633 11.9672 17.6453C12.275 17.3508 12.5922 17.0461 12.8711 16.9312C13.1266 16.825 13.55 16.8187 13.9602 16.8125C14.7227 16.8008 15.5867 16.7883 16.1875 16.1875C16.7883 15.5867 16.8008 14.7227 16.8125 13.9602C16.8187 13.55 16.825 13.1266 16.9312 12.8711C17.0461 12.593 17.3508 12.275 17.6453 11.9672C18.1633 11.4281 18.75 10.8156 18.75 10C18.75 9.18437 18.1633 8.57266 17.6453 8.03281ZM8.75 13.1255C8.66787 13.1255 8.58654 13.1093 8.51066 13.0779C8.43479 13.0464 8.36586 13.0003 8.30781 12.9422L6.43281 11.0672C6.31554 10.9499 6.24965 10.7909 6.24965 10.625C6.24965 10.4591 6.31554 10.3001 6.43281 10.1828C6.55009 10.0655 6.70915 9.99965 6.875 9.99965C7.04085 9.99965 7.19991 10.0655 7.31719 10.1828L8.75 11.6164L12.6828 7.68281C12.7409 7.6247 12.8098 7.5786 12.8857 7.54715C12.9615 7.5157 13.0429 7.49951 13.125 7.49951C13.2071 7.49951 13.2885 7.5157 13.3643 7.54715C13.4402 7.5786 13.5091 7.6247 13.5672 7.68281C13.6253 7.74086 13.6714 7.80979 13.7029 7.88566C13.7343 7.96154 13.7505 8.04287 13.7505 8.125C13.7505 8.20713 13.7343 8.28846 13.7029 8.36434C13.6714 8.44021 13.6253 8.50914 13.5672 8.56719L9.19219 12.9422C9.13414 13.0003 9.06521 13.0464 8.98934 13.0779C8.91346 13.1093 8.83213 13.1255 8.75 13.1255Z"
                                                                        fill="black" />
                                                                </svg>
                                                                <div class="text">Verified Purchase</div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="list-star d-flex justify-content-center gap-2">
                                                        @for ($i = 1; $i <= 5; $i++) <svg width="14" height="14"
                                                            viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M14 5.4091L8.913 5.07466L6.99721 0.261719L5.08143 5.07466L0 5.4091L3.89741 8.7184L2.61849 13.7384L6.99721 10.9707L11.376 13.7384L10.097 8.7184L14 5.4091Z"
                                                                fill="{{ $i <= $review->rating ? '#EF9122' : '#ccc' }}"></path>
                                                            </svg>
                                                            @endfor
                                                    </div>
                                                </div>

                                                <p class="h6 desc mt-2">
                                                    {{ $review->comment }}
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($totalReviews > 1)
                                    <div class="text-center mt-4">
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
                                            <li><a href="#" wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a></li>
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
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal modalCentered fade modal-ask_question" id="ReviewsModal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-heading">
                    <h2 class="fw-normal">Write Your Review</h2>
                    <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
                </div>
                <form class="form-ask mb-0" wire:submit.prevent="submit">

                    <div class="your-rating mb-3">
                        <div class="h4 fw-4 text-black">Your rating:</div>
                        <div class="selectrating" id="star-rating">
                          <input type="radio" id="star5" name="rating" value="5" wire:model="rating">
                            <label for="star5" title="5 stars"></label>

                            <input type="radio" id="star4" name="rating" value="4" wire:model="rating">
                            <label for="star4" title="4 stars"></label>

                            <input type="radio" id="star3" name="rating" value="3" wire:model="rating">
                            <label for="star3" title="3 stars"></label>

                            <input type="radio" id="star2" name="rating" value="2" wire:model="rating">
                            <label for="star2" title="2 stars"></label>

                            <input type="radio" id="star1" name="rating" value="1" wire:model="rating">
                            <label for="star1" title="1 star"></label>
                        </div>

                        @error('rating')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-content">
                        @guest
                            <div class="box-field group-2">
                                <fieldset class="mb-3">
                                    <input type="text" placeholder="Your name" wire:model="name" class="@error('name') error_border @enderror">
                                    @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>

                                <fieldset class="mb-3">
                                    <input type="email" placeholder="Your email" wire:model="email" class="@error('email') error_border @enderror">
                                    @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                            </div>
                        @endguest
                        <fieldset class="box-field">
                            <textarea rows="4" placeholder="Write your review..." wire:model="comment" class="@error('comment') error_border @enderror"></textarea>
                            @error('comment')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <button type="submit" class="tf-btn animate-btn w-100" style="margin-top: 20px;">
                        <span wire:loading.remove wire:target="submit">Submit</span>
                        <span wire:loading wire:target="submit" class="formloader"></span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>