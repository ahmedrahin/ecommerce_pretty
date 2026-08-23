<div class="app-navbar-item ms-1 ms-md-4 notification-items" wire:poll.visible.10000ms>

    <a class="position-relative me-4 btn-notification" href="{{ route('contact.message') }}">
        <i class="ki-duotone ki-sms fs-4">
            <span class="path1"></span>
            <span class="path2"></span>
        </i> Unread Messages
        @if ($messages > 0)
        <span class="position-absolute top-0 start-100 translate-middle  badge badge-circle badge-primary">{{ $messages
            }}</span>
        @endif
    </a>

    <a class="position-relative me-4 btn-notification" href="{{ route('question.product') }}">
        <i class="ki-duotone ki-sms fs-4">
            <span class="path1"></span>
            <span class="path2"></span>
        </i> Unread Questions
        @if ($question > 0)
        <span class="position-absolute top-0 start-100 translate-middle  badge badge-circle badge-primary">{{ $question
            }}</span>
        @endif
    </a>

    <a class="position-relative me-4b btn-notification" href="{{ route('order-management.order.index') }}">
        <i class="ki-duotone ki-basket fs-4">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
        </i> New Orders
        @if ($orders > 0)
        <span class="position-absolute top-0 start-100 translate-middle  badge badge-circle badge-primary">{{ $orders
            }}</span>
        @endif
    </a>

    <!--begin::Menu- wrapper-->
    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
        data-kt-menu-trigger="{default: 'click'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
        id="kt_menu_item_wow" wire:click="markAllAsRead" style="margin-left: 10px;">
        <i class="ki-duotone ki-notification-on fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
            <span class="path5"></span>
        </i>

        @if ($hasUnread)
        <span
            class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
        @endif
    </div>

    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true"
        id="kt_menu_notifications" style="box-shadow:0px 10px 9px 4px #7d7d7d05 !important;" wire:ignore>
        <div class="card card-xl-stretch mb-0 mb-xl-8" style="margin-bottom: 0 !important;">
            <!--begin::Header-->
            <div class="card-header border-0" style="border-bottom: 1px solid #eee !important;margin-bottom: 5px;">
                <h3 class="card-title fw-bold text-dark">Notifications</h3>
                <div class="card-toolbar">

                </div>
            </div>

            @livewire('notifications.notification-list')

        </div>
    </div>

</div>
