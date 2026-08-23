<div class="sidebar-account sidebar-content-wrap sticky-top">

    <div id="profile_img">
        @include('frontend.pages.user.profile-img')
    </div>

    <ul class="my-account-nav">
        <li>
            <a href="{{ route('user.dashboard') }}"
                class="my-account-nav_item h5 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="icon icon-circle-four"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('user.orders') }}"
                class="my-account-nav_item h5 {{ request()->routeIs('user.orders') || request()->routeIs('order.invoice') ? 'active' : '' }}">
                <i class="icon icon-box-arrow-down"></i>
                Orders
            </a>
        </li>
        <li>
            <a href="{{ route('user.edit.profile') }}" class="my-account-nav_item h5 {{ request()->routeIs('user.edit.profile') ? 'active' : '' }}">
                <i class="icon icon-setting"></i>
                Setting
            </a>
        </li>
        <li>
            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  class="my-account-nav_item h5">
                <i class="icon icon-sign-out"></i>
                Log out
            </a>
        </li>
    </ul>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
