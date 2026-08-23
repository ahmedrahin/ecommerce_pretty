@extends('frontend.layout.app')

@section('page-title')
    Login
@endsection

@section('page-css')
   <style>
    .flat-spacing {
        padding-top: 70px;
        padding-bottom: 80px;
    }
   </style>
@endsection

@section('body-content')

    <section class="s-page-title">
        <div class="container">
            <div class="content">
                <h1 class="title-page">Login</h1>
                <ul class="breadcrumbs-page">
                   <li><a href="{{ url('/') }}" class="h6 link">Home</a></li>
                    <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                    <li>
                        <h6 class="current-page fw-normal">Login</h6>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="flat-spacing">
        <div class="container">
            <div class=row">
                <div class="col-md-4 offset-md-4">
                    <h2 class="heading" style="margin-bottom: 20px;">Login</h2>

                    <form id="loginForm" class="form-login">
                        <div class="list-ver">
                            <fieldset>
                                <input type="text" id="email" name="email" placeholder="Enter your email address *">
                                <div class="text-danger mt-2" id="emailError"></div>
                            </fieldset>

                            <fieldset class="password-wrapper mb-8">
                                <input class="password-field" id="password" name="password" type="password" placeholder="Password *">
                                <span class="toggle-pass icon-show-password"></span>
                                <div class="text-danger mt-2" id="passwordError"></div>
                            </fieldset>

                            <div class="check-bottom">
                                <div class="checkbox-wrap">
                                    <input id="remember" type="checkbox" class="tf-check">
                                    <label for="remember" class="h6">Keep me signed in</label>
                                </div>
                                <h6>
                                    <a href="{{ route('password.request') }}" class="link">Forgot your password?</a>
                                </h6>
                            </div>
                        </div>

                        <button type="submit" id="loginButton" class="tf-btn animate-btn w-100">
                            <span class="text">Login</span>
                            <span class="formloader" style="display: none;"></span>
                        </button>

                        <div class="orther-log list-ver">
                            <div class="text-social">
                                <span class="br-line"></span>
                                <p class="h6 text-nowrap">Don't have an account?</p>
                                <span class="br-line"></span>
                            </div>
                        </div>

                         <a href="{{ route('register') }}" class="tf-btn style-line">
                            Register new account
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </section>


@endsection

@section('page-script')
    <script>
        jQuery(function($) {
            $("#loginForm").on("submit", function (e) {
                e.preventDefault();

                // Clear previous errors
                $("#emailError").text("");
                $("#passwordError").text("");

            $(".text-danger").text("");
            $("#email, #password").removeClass("error_border");

            $(".formloader").css("display", "inline-block");
            $(".text").css("display", "none");
            $("#loginButton").prop("disabled", true);


                // Form data
                let formData = {
                    email: $("#email").val(),
                    password: $("#password").val(),
                    remember: $("#remember").is(":checked"),
                };

                $.ajax({
                    url: "{{ route('user.login') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);

                        // Redirect to homepage on successful login
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    },
                    error: function (xhr) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);

                        if (xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;

                            if (errors.email) {
                                $("#emailError").text(errors.email);
                                $("#email").addClass("error_border");
                            }

                            if (errors.password) {
                                $("#passwordError").text(errors.password[0]);
                                $("#password").addClass("error_border");
                            }
                        }
                    }
                });
            });
        });
    </script>

    @if (session('info'))
        <script>
        toastr.error("{{ session('info') }}");
        </script>
    @endif
@endsection
