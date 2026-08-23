@extends('frontend.layout.app')

@section('page-title')
    My Wishlist
@endsection


@section('page-css')
    <link href="{{ asset('frontend/style/accounts.min.12.css') }}" type="text/css" rel="stylesheet" media="screen" />
    <style>
        .status-container.pending .status {
            color: #6c757d !important;
        }
        .status-container.processing .status {
            color: #17a2b8 !important; /* Blue for Processing */
        }
        .status-container.delivered .status {
            color: #28a745 !important; /* Green for Delivered */
        }
        .status-container.completed .status {
            color: #218838 !important; /* Darker Green for Completed */
        }
        .status-container.canceled .status {
            color: #dc3545 !important; /* Red for Cancelled */
        }
        .status-container.fake .status {

        }
        .o-card .c-head .right .status {
            font-weight: 600;
            font-size: 14px;
        }

    </style>
@endsection

@section('body-content')

<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="material-icons" title="Home">home</i></a></li>
            <li><a href="{{ route('user.dashboard') }}">Account</a></li>
            <li><a href="">My Wishlist</a></li>
        </ul>
    </div>
</section>

<div class="container ac-layout">
    <div class="ac-header">
        <div class="left">
            <span class="avatar">
                <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('frontend/image/user.png') }}"
                    width="80" height="80" alt="Ra"></span>
            <div class="name">
                <p>Hello,</p>
                <p class="user">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    @include('frontend.pages.user.user-menu')

    <div class="ac-title">
        <h1>My Wishlist</h1>
    </div>

    <livewire:frontend.wishlist.wishlist-list :user_id="$user->id" />

</div>

@endsection
