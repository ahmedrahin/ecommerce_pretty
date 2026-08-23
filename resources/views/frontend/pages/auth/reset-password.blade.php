@extends('frontend.layout.app')

@section('page-title')
    Reset Password
@endsection

@section('page-css')
    <style>
        .form-login {
            gap: 22px !important;
        }
    </style>
@endsection

@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">@yield('page-title')</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ url('/') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">@yield('page-title')</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="flat-spacing">
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <h2 class="heading" style="margin-bottom: 20px;">Reset Your Password</h2>

                    <form method="POST" action="{{ route('password.update') }}" class="form-login">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->token }}">
                        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                        <div class="list-ver">
                            <fieldset class="password-wrapper">
                                <input class="password-field @error('password') error_border @enderror"
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Enter new password *"
                                    >
                                <span class="toggle-pass icon-show-password"></span>
                                @error('password')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </fieldset>

                            <fieldset class="password-wrapper">
                                <input class="password-field"
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Confirm new password *"
                                    >
                                <span class="toggle-pass icon-show-password"></span>
                            </fieldset>
                        </div>

                        <button type="submit" class="tf-btn animate-btn w-100">
                            Confirm
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('user.login') }}" class="link">← Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
