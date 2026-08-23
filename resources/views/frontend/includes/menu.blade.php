<header class="tf-header header-fix">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-3 d-xl-none">
                <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-mobile-menu">
                    <span></span>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 text-center text-xl-start">
                <a href="{{ url('/') }}" class="logo-site justify-content-center justify-content-xl-start">
                    <img src="{{ asset(config('app.logo')) }}" alt="Logo">
                </a>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <nav class="box-navigation">
                    <ul class="box-nav-menu">
                        <li class="menu-item position-relative">
                            <a href="{{ route('homepage') }}" class="item-link {{ request()->routeIs('homepage') ? 'active' : '' }}">HOME</a>
                        </li>
                        <li class="menu-item position-relative">
                            <a href="{{ route('about') }}" class="item-link {{ request()->routeIs('about') ? 'active' : '' }}">ABOUT US</a>
                        </li>
                        <li class="menu-item position-relative">
                            <a href="{{ route('shop') }}" class="item-link {{ request()->routeIs('shop') ? 'active' : '' }}">SHOP</a>
                        </li>
                        {{-- <li class="menu-item">
                            <a href="javascript:void(0)" class="item-link">CATEGORIES <i class="icon icon-caret-down"></i></a>
                            <div class="sub-menu mega-menu">
                                <div class="container">
                                    <div class="row">
                                        @include('frontend.includes.category_menu')
                                    </div>
                                </div>
                            </div>
                        </li> --}}
                        {{-- <li class="menu-item position-relative">
                            <a href="javascript:void(0)" class="item-link">BLOG</a>
                        </li> --}}
                        <li class="menu-item position-relative">
                            <a href="{{ route('contact') }}" class="item-link {{ request()->routeIs('contact') ? 'active' : '' }}">CONTACT US</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-xl-3 col-md-4 col-3">
                <ul class="nav-icon-list">
                    <li class="d-none d-lg-flex">
                        @if(!auth()->check())
                            <a class="nav-icon-item link" href="{{ route('user.login') }}"><i class="icon icon-user"></i></a>
                        @else
                            <a class="nav-icon-item link" href="{{ route('user.dashboard') }}"><i class="icon icon-user"></i></a>
                        @endif
                    </li>
                    <li class="d-none d-md-flex">
                        <a class="nav-icon-item link" href="#search" data-bs-toggle="modal">
                            <i class="icon icon-magnifying-glass"></i>
                        </a>
                    </li>
                    <li class="d-none shop-cart d-sm-flex m-0">
                        <a class="nav-icon-item link" href="{{ route('wishlist') }}"><i class="icon icon-heart"></i><livewire:frontend.wishlist.count-wishlist /></a>

                    </li>
                    <li class="shop-cart" data-bs-toggle="offcanvas" data-bs-target="#shoppingCart">
                        <a class="nav-icon-item link" href="#shoppingCart" data-bs-toggle="offcanvas">
                            <i class="icon icon-shopping-cart-simple"></i>
                             <livewire:frontend.cart.btn-cart />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<livewire:frontend.cart.shopping-cart />
<livewire:frontend.shop.search-box />

<!-- Mobile Menu -->
<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close-popup" data-bs-dismiss="offcanvas">
        <i class="icon-close"></i>
    </span>
    <div class="canvas-header">
        <p class="text-logo-mb"><img src="{{ asset(config('app.logo')) }}" alt="Logo"></p>
        @if(!auth()->check())
            <a href="{{ route('user.login') }}" class="tf-btn type-small style-2">
                Login
                <i class="icon icon-user"></i>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}" class="tf-btn type-small style-2" style="padding: 13px;border-radius: 50%;">
                <i class="icon icon-user"></i>
            </a>
        @endif
        <span class="br-line"></span>
    </div>
    <div class="canvas-body">
        <div class="mb-content-top">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation"></ul>
        </div>
        <div class="group-btn">
            <a href="{{ route('wishlist') }}" class="tf-btn type-small style-2">
                Wishlist
                <i class="icon icon-heart"></i>
            </a>
            <div data-bs-dismiss="offcanvas">
                <a href="#search" data-bs-toggle="modal" class="tf-btn type-small style-2">
                    Search
                    <i class="icon icon-magnifying-glass"></i>
                </a>
            </div>
        </div>

        <div class="flow-us-wrap">
            <h5 class="title">Follow us on</h5>
            <ul class="tf-social-icon">
                @if (!empty(config('app.facebook')))
                    <li>
                        <a href="{{ config('app.facebook') }}" target="_blank" class="social-facebook">
                            <span class="icon"><i class="icon-fb"></i></span>
                        </a>
                    </li>
                @endif

                @if (!empty(config('app.instra')))
                    <li>
                        <a href="{{ config('app.instra') }}" target="_blank" class="social-instagram">
                            <span class="icon"><i class="icon-instagram-logo"></i></span>
                        </a>
                    </li>
                @endif

                @if (!empty(config('app.twitter')))
                    <li>
                        <a href="{{ config('app.twitter') }}" target="_blank" class="social-x">
                            <span class="icon"><i class="icon-x"></i></span>
                        </a>
                    </li>
                @endif

                @if (!empty(config('app.tiktok')))
                    <li>
                        <a href="{{ config('app.tiktok') }}" target="_blank" class="social-tiktok">
                            <span class="icon"><i class="icon-tiktok"></i></span>
                        </a>
                    </li>
                @endif
                @if (!empty(config('app.whatsapp')))
                    <li>
                        <a href="{{ config('app.whatsapp') }}" target="_blank" class="social-whatsapp">
                            <span class="icon"><i class="bi bi-whatsapp"></i></span>
                        </a>
                    </li>
                @endif

                @if (!empty(config('app.youtube')))
                    <li>
                        <a href="{{ config('app.youtube') }}" target="_blank" class="social-youtube">
                            <span class="icon"><i class="bi bi-youtube"></i></span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

    </div>
    <div class="canvas-footer">

    </div>
</div>
