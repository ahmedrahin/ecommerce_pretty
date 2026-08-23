@extends('frontend.layout.app')

@section('page-title')
    Register 
@endsection

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --olive: #4a4a2e;
            --cream: #f5f2ec;
            --charcoal: #1c1c1c;
            --gold: #5d060e;
            --red: #c0392b;
            --light-gray: #e8e5df;
            --transition: all 0.3s ease;
        }

        .register-page-container {
            min-height: calc(100vh - 200px);
            background: linear-gradient(135deg, var(--cream) 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }

        .register-card {
            max-width: 520px;
            width: 100%;
            background: white;
            border-radius: 32px;
            padding: 32px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }

        /* Logo Section */
        .register-logo {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo-img {
            max-width: 200px;
            height: auto;
            max-height: 100px;
            object-fit: contain;
        }

        /* Header */
        .register-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .register-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--charcoal);
            margin-bottom: 8px;
        }

        .register-header p {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Form */
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 0;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--charcoal);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0 !important;
        }

        .form-label i {
            color: var(--gold);
            font-size: 1rem;
        }

        .form-label .required-star {
            color: var(--red);
            font-size: 0.8rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #dcdcdc !important;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            background: white;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(93, 6, 14, 0.1);
        }

        .form-control-custom.error-border {
            border-color: var(--red);
        }

        /* Password Wrapper */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control-custom {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .toggle-password:hover {
            color: var(--gold);
        }

        /* Register Button */
        .register-btn {
            background: var(--gold);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(93, 6, 14, 0.3);
        }

        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .formloader {
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Login Link */
        .login-prompt {
            text-align: center;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .login-prompt p {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }

        .login-prompt a {
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-prompt a:hover {
            color: var(--olive);
        }

        /* Error Messages */
        .text-danger {
            font-size: 0.75rem;
            color: var(--red);
            margin-top: 4px;
            padding-top: 0 !important;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .register-card {
                padding: 28px 24px;
            }

            .register-header h2 {
                font-size: 1.5rem;
            }

            .logo-img {
                max-width: 150px;
            }
        }
    </style>
@endsection

@section('body-content')
    <div class="register-page-container">
        <div class="register-card">
            <!-- Logo Section -->
            <div class="register-logo">
                <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" class="logo-img">
            </div>

            <!-- Header -->
            <div class="register-header">
                <h2>Create Account</h2>
                <p>Join us to start your shopping journey</p>
            </div>

            <!-- Register Form -->
            <form method="post" id="registerForm" class="register-form">
                @csrf

                <!-- Full Name Field -->
                <div class="form-group">
                    <label class="form-label" for="name">
                        <i class="bi bi-person-fill"></i> Full Name <span class="required-star">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Enter your full name" 
                           id="name" 
                           class="form-control-custom" />
                    <div class="text-danger" id="nameError"></div>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="bi bi-envelope-fill"></i> Email Address <span class="required-star">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="your@email.com" 
                           id="email" 
                           class="form-control-custom" />
                    <div class="text-danger" id="emailError"></div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="bi bi-lock-fill"></i> Password <span class="required-star">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               name="password" 
                               placeholder="Create a password" 
                               class="form-control-custom" 
                               id="password" />
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <div class="text-danger" id="passwordError"></div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        <i class="bi bi-shield-lock-fill"></i> Confirm Password <span class="required-star">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input type="password" 
                               name="password_confirmation" 
                               placeholder="Confirm your password" 
                               class="form-control-custom" 
                               id="password_confirmation" />
                        <button type="button" class="toggle-password" id="toggleConfirmPassword">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <div class="text-danger" id="passwordConfirmationError"></div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="register-btn" id="registerButton">
                    <span class="text">Create Account</span>
                    <span class="formloader" style="display: none;"></span>
                </button>

                <!-- Login Link -->
                <div class="login-prompt">
                    <p>Already have an account? <a href="{{ route('user.login') }}">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle Password Visibility for Password field
        $('#togglePassword').on('click', function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        // Toggle Password Visibility for Confirm Password field
        $('#toggleConfirmPassword').on('click', function() {
            const confirmInput = $('#password_confirmation');
            const icon = $(this).find('i');
            
            if (confirmInput.attr('type') === 'password') {
                confirmInput.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                confirmInput.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        // Form submission with AJAX
        $("#registerForm").on("submit", function(e) {
            e.preventDefault();

            // Clear previous errors
            $(".text-danger").text("");
            $("#name, #email, #password, #password_confirmation").removeClass("error-border");

            // Show loader
            $(".formloader").css("display", "inline-block");
            $(".text").css("display", "none");
            $("#registerButton").prop("disabled", true);

            let formData = {
                name: $("#name").val(),
                email: $("#email").val(),
                password: $("#password").val(),
                password_confirmation: $("#password_confirmation").val(),
            };

            $.ajax({
                url: "{{ route('user.register') }}",
                type: "POST",
                data: formData,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $(".formloader").css("display", "none");
                    $(".text").css("display", "block");
                    $("#registerButton").prop("disabled", false);

                    // Show success message
                    if (response.message) {
                        toastr.success(response.message);
                    }

                    // Redirect to dashboard on successful registration
                    setTimeout(function() {
                        window.location.href = "{{ route('user.dashboard') }}";
                    }, 500);
                },
                error: function(xhr) {
                    $(".formloader").css("display", "none");
                    $(".text").css("display", "block");
                    $("#registerButton").prop("disabled", false);

                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.name) {
                            $("#nameError").text(errors.name[0]);
                            $("#name").addClass("error-border");
                        }
                        if (errors.email) {
                            $("#emailError").text(errors.email[0]);
                            $("#email").addClass("error-border");
                        }
                        if (errors.password) {
                            $("#passwordError").text(errors.password[0]);
                            $("#password").addClass("error-border");
                        }
                        if (errors.password_confirmation) {
                            $("#passwordConfirmationError").text(errors.password_confirmation[0]);
                            $("#password_confirmation").addClass("error-border");
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error("Something went wrong. Please try again.");
                    }
                }
            });
        });

        // Remove error border on input
        $("#name, #email, #password, #password_confirmation").on("input", function() {
            $(this).removeClass("error-border");
            // Clear the specific error message for this field
            $(this).siblings(".text-danger").text("");
        });
    });

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection