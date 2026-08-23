@extends('frontend.layout.app')

@section('page-title')
    Edit Profile
@endsection

@push('scripts')

    <style>
        @media (min-width: 1200px) {
            .btn-add-to-cart {
                width: 25%;
                margin: auto;
            }
            .savePass{
                margin-top: 30px;
                display: block;
            }
        }
    </style>

@endpush

@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">@yield('page-title')</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ route('homepage') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li><a href="{{ route('user.dashboard') }}" class="h6 link">My Dashboard</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">@yield('page-title')</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <div class="btn-sidebar-mb d-lg-none left">
        <button data-bs-toggle="offcanvas" data-bs-target="#mbSidebar">
            <i class="icon icon-sidebar"></i>
        </button>
    </div>
    <div class="offcanvas offcanvas-start canvas-sidebar" id="mbSidebar">
        <div class="canvas-wrapper">
            <div class="canvas-header">
                <span class="title h4 fw-bold">My Acount</span>
                <span class="icon-close link icon-close-popup" data-bs-dismiss="offcanvas"></span>
            </div>
            <div class="canvas-body sidebar-mobile-append sidebar-account"></div>
        </div>
    </div>

    <section class="flat-spacing">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 d-none d-xl-block">
                    @include('frontend.pages.user.user-menu')
                </div>

                 <div class="col-xl-9">
                    <div class="my-account-content">
                       
                        <livewire:frontend.user.edit-profile :user_id="$user->id" />

                        <livewire:frontend.user.change-password :user_id="$user->id" />

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
