<div class="allReview">
    @foreach($reviews as $review)
        <ul>
            <li>
                @php
                    $user = $review->user;
                    $avatar = ($user && $user->avatar && file_exists(public_path($user->avatar)))
                        ? $user->avatar
                        : 'uploads/user/user.jpg';
                @endphp
                <img src="{{ asset($avatar) }}" alt="User Avatar">
            </li>
            <li class="messageItem">
                <span>
                    @if($user)
                        @php
                            // Determine route based on user type
                            $userRoute = $user->isAdmin == 1
                                ? route('admin-management.admin-list.show', $user->id)
                                : route('user-management.users.show', $user->id);
                        @endphp
                        <a href="{{ $userRoute }}" target="_blank">{{ $user->name }}</a>
                    @else
                        {{ $review->name }}
                    @endif

                    @if(is_null($review->user_id) || !$user)
                        <span style="display: inline;font-size: 11px;opacity: .7;font-weight: 400;">(guest)</span>
                    @endif
                </span>

                <p>{{ $review->comment }}</p>

                <div class="rating">
                    @php
                        $fullStars = floor($review->rating);
                        $halfStar = ($review->rating - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    @endphp

                    @for($i = 0; $i < $fullStars; $i++)
                        <div class="rating-label checked">
                            <i class="ki-duotone ki-star fs-6"></i>
                        </div>
                    @endfor

                    @if($halfStar)
                        <div class="rating-label checked">
                            <i class="ki-duotone ki-star-half fs-6"></i>
                        </div>
                    @endif

                    @for($i = 0; $i < $emptyStars; $i++)
                        <div class="rating-label">
                            <i class="ki-duotone ki-star fs-6"></i>
                        </div>
                    @endfor
                </div>

                <div class="date">
                    {{ \Carbon\Carbon::parse($review->created_at)->format('M-d, Y') }}
                </div>
            </li>
        </ul>
    @endforeach

</div>
