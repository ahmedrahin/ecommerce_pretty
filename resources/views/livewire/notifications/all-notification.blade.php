<div class="row pt-6">
    <div class="col-md-8">
        <div class="card card-xl-stretch mb-0 mb-xl-8" style="margin-bottom: 0 !important;">
            <!--begin::Header-->
            <div class="card-header border-0" style="border-bottom: 1px solid #eee !important;margin-bottom: 5px;">
                <h3 class="card-title align-items-start flex-column py-3">
                    <span class="card-label fw-bold text-dark">All Notification</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6 pt-1">You can see last 100 notifications.</span>
                </h3>
                <div class="card-toolbar">

                </div>
            </div>

            <div class="card-body pt-0 pb-8 ">
                @php
                $hasNotifications = collect($groupedNotifications)->flatten()->isNotEmpty();
                @endphp

                @if (!$hasNotifications)
                <div class="text-muted text-center pt-6 pb-8" style="font-size: 16px;">No notifications yet.</div>
                @else
                @foreach ($groupedNotifications as $group => $notifications)
                @if ($notifications->count())
                <h6 class="text-gray-600 fw-bold mb-4 mt-5">{{ $group }}</h6>

                @foreach ($notifications as $notification)
                @php
                switch ($notification->type) {
                case 'order':
                $icon = 'ki-basket';
                $color = 'success';
                $title = 'New Order';
                $linkId = $notification->order_id;
                $route = route('order-management.order.show', $linkId);
                break;
                case 'message':
                $icon = 'ki-message-text-2';
                $color = 'info';
                $title = 'New Message';
                $linkId = $notification->contact_message_id;
                $route = route('contact.message.details', $linkId);
                break;
                case 'question':
                $icon = 'ki-message-text-2';
                $color = 'info';
                $title = 'New Product Question';
                $linkId = $notification->question_id;
                $route = route('question.details', $linkId);
                break;
                case 'product':
                $icon = 'ki-medal-star';
                $color = 'warning';
                $title = 'Upload an new event';
                $linkId = $notification->product_id;
                $route = route('product-management..show', $linkId);
                break;
                default:
                $icon = 'ki-information';
                $color = 'secondary';
                $title = ucfirst($notification->type);
                $route = '#';
                }
                @endphp

                <a href="{{ $route }}">
                    <div
                        class="d-flex align-items-center bg-light-{{ $color }} rounded p-5 {{ !$loop->last ? 'mb-4' : '' }}">
                        <i class="ki-duotone {{ $icon }} text-{{ $color }} fs-1 me-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <div class="flex-grow-1 me-2">
                            <span class="fw-bold text-gray-800 text-hover-primary fs-6">
                                {{ $title }}
                            </span>
                            <span class="text-muted fw-semibold d-block">
                                 @if ($notification->created_at->diffInSeconds(now()) < 59)
                                    now
                                @else
                                    {{ $notification->created_at->diffForHumans() }}
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
                @endif
                @endforeach
                @endif

            </div>
        </div>
    </div>
