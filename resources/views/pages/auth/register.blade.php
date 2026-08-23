@extends('frontend.layout.app')

@section('page-title')
    Register
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
                <h1 class="title-page">Register</h1>
                <ul class="breadcrumbs-page">
                    <li><a href="{{ url('/') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">Register</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>
   
    <section class="flat-spacing">
        <div class="container">
            <div class="s-log">
                <div class="col-left">
                    <h2 class="heading">Register</h2>

                    <form id="registerForm" class="form-login">
                        @csrf
                        <div class="list-ver">

                            <fieldset>
                                <input type="text" id="name" name="firstname" placeholder="Enter your name *">
                                <div class="text-danger mt-2" id="nameError"></div>
                            </fieldset>

                            <fieldset>
                                <input type="text" id="email" name="email" placeholder="Enter your email address *">
                                <div class="text-danger mt-2" id="emailError"></div>
                            </fieldset>

                            <fieldset class="password-wrapper">
                                <input class="password-field" id="password" name="password" type="password" placeholder="Password *">
                                <span class="toggle-pass icon-show-password"></span>
                                <div class="text-danger mt-2" id="passwordError"></div>
                            </fieldset>

                            <fieldset class="password-wrapper">
                                <input class="password-field" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password *">
                                <span class="toggle-pass icon-show-password"></span>
                                <div class="text-danger mt-2" id="passwordConfirmationError"></div>
                            </fieldset>

                            <div class="check-bottom">
                                <div class="checkbox-wrap">
                                    <input id="remember" type="checkbox" class="tf-check">
                                    <label for="remember" class="h6">Keep me signed in</label>
                                </div>
                            </div>

                        </div>

                        <button type="submit" id="registerButton" class="tf-btn animate-btn w-100">
                            <span class="text">Register</span>
                            <span class="formloader" style="display: none;"></span>
                        </button>
                    </form>
                </div>

                <div class="col-right">
                    <h2 class="heading">Have An Account</h2>
                    <p class="h6 text-sub">
                        Welcome back, log in to your account to enhance your shopping experience, receive coupons, and the best discount codes.
                    </p>
                    <a href="{{ route('user.login') }}" class="btn_log tf-btn animate-btn">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('page-script')
    <script>

        jQuery(function($) {
            $("#registerForm").on("submit", function (e) {
                e.preventDefault();

                // Clear previous errors
                $(".text-danger").text("");
                $("#name, #email, #password, #password_confirmation").removeClass("error_border");

                let formData = {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    password_confirmation: $("#password_confirmation").val(),
                };

                $(".formloader").css("display", "inline-block");
                $(".text").css("display", "none");
                $("#loginButton").prop("disabled", true);

                $.ajax({
                    url: "{{ route('user.register') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);

                        window.location.href = "{{ route('user.dashboard') }}";
                    },
                    error: function (xhr) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);

                        if (xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;

                            if (errors.name || errors.firstname) {
                                $("#nameError").text(errors.name ? errors.name[0] : errors.firstname[0]);
                                $("#name").addClass("error_border");
                            }
                            if (errors.email) {
                                $("#emailError").text(errors.email[0]);
                                $("#email").addClass("error_border");
                            }
                            if (errors.password) {
                                $("#passwordError").text(errors.password[0]);
                                $("#password").addClass("error_border");
                            }
                            if (errors.password_confirmation) {
                                $("#passwordConfirmationError").text(errors.password_confirmation[0]);
                                $("#password_confirmation").addClass("error_border");
                            }
                        }
                    }
                });
            });

            // Remove error border on input
            $("#name, #email, #password, #password_confirmation").on("input", function () {
                $(this).removeClass("error_border");
            });
        });


    </script>
@endsection

