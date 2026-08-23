@extends('frontend.layout.app')

@section('page-title')
    Forgot Password
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
                    <h2 class="heading" style="margin-bottom:20px;">Forgot Your Password?</h2>

                    <form id="otpForm" class="form-login">
                        @csrf
                        <div class="list-ver">
                            <fieldset>
                                <input type="text" id="email" name="email" placeholder="Enter your email address *">
                                <div class="text-danger mt-2" id="emailError"></div>
                            </fieldset>
                        </div>

                        <button type="submit" id="otpButton" class="tf-btn animate-btn w-100">
                            <span class="text">Continue</span>
                            <span class="formloader" style="display: none;"></span>
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

@section('page-script')
    <script>
        jQuery(function($) {
            $("#otpForm").on("submit", function(e) {
                e.preventDefault();

                // Clear previous errors
                $("#emailError").text("");

                // Show spinner and disable button
                $(".text-danger").text("");
                $("#email, #password").removeClass("error_border");

                $(".formloader").css("display", "inline-block");
                $(".text").css("display", "none");
                $("#loginButton").prop("disabled", true);

                let formData = {
                    email: $("#email").val(),
                };

                $.ajax({
                    url: "{{ route('password.email') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);
                         $('#otpForm')[0].reset();

                         $("#loginButton").prop("disabled", false);

                        message('success', 'Password reset link sent to your email!');
                    },
                    error: function(xhr) {
                        $(".formloader").css("display", "none");
                        $(".text").css("display", "block");
                        $("#loginButton").prop("disabled", false);

                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            $("#emailError").text(errors.email);
                            $("#email").addClass("error_border");
                        }
                    },
                    complete: function() {
                        $("#spinner").addClass("d-none");
                        $("#otpButton").prop("disabled", false);
                    },
                });
            });
        });
    </script>
@endsection
