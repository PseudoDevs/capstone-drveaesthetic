@extends('layouts.app')

@section('title', 'Register - ' . config('app.name'))

@section('content')
    <!--====================================================================
                                    Start Register Section
        =====================================================================-->
    <section class="login-section pt-150 rpt-100 pb-150 rpb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="login-form-wrapper">
                        <div class="section-title text-center mb-50">
                            <span class="sub-title mb-15">Join Us</span>
                            <h2>Create Your Account</h2>
                            <p>Sign up to book appointments and access our services</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-30">
                                <strong>Registration Failed:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-30">
                                <strong>Error:</strong> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.create-account') }}" class="login-form" id="registration-form">
                            @csrf

                            <div class="form-group mb-25">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter your full name" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-25">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-25">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-25">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm your password" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-30">
                                <button type="submit" class="theme-btn w-100">
                                    <span>Create Account</span>
                                </button>
                            </div>

                            <div class="divider mb-30">
                                <span>Or continue with</span>
                            </div>

                            <div class="social-login mb-30">
                                <a href="#" class="google-login-btn" id="google-login-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                            fill="#4285F4" />
                                        <path
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                            fill="#34A853" />
                                        <path
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                            fill="#FBBC05" />
                                        <path
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                            fill="#EA4335" />
                                    </svg>
                                    <span id="google-btn-text">Continue with Google</span>
                                </a>
                            </div>

                            <div class="text-center">
                                <p>Already have an account? <a href="{{ route('login') }}" class="register-link">Sign in</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====================================================================
                                    End Register Section
        =====================================================================-->
@endsection

@push('styles')
    <style>
        .login-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-form-wrapper {
            background: white;
            padding: 60px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .section-title .sub-title {
            color: #007bff;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title h2 {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #666;
            font-size: 16px;
        }

        .form-group label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 50px;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            border: none;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-danger strong {
            font-weight: 600;
        }

        .alert-danger ul {
            margin-top: 8px;
            padding-left: 20px;
        }

        .alert-danger li {
            margin-bottom: 4px;
        }

        .theme-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            height: 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .theme-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 30px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e8ed;
        }

        .divider span {
            background: white;
            color: #666;
            padding: 0 20px;
            font-size: 14px;
        }

        .google-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 50px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            background: white;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .google-login-btn:hover {
            border-color: #007bff;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
        }

        .google-login-btn svg {
            margin-right: 10px;
        }

        .register-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-form-wrapper {
                padding: 40px 30px;
            }

            .section-title h2 {
                font-size: 28px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registration-form');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            const googleBtn = document.getElementById('google-login-btn');
            const googleBtnText = document.getElementById('google-btn-text');

            form.addEventListener('submit', function(e) {
                // Show loading state only
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Creating Account...</span>';

                // Let the form submit normally - server will handle all validation
            });

            // Google Sign-in Handler
            googleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Show loading state
                googleBtn.style.pointerEvents = 'none';
                googleBtnText.textContent = 'Connecting to Google...';

                // Redirect to Google OAuth
                window.location.href = '/auth/google';
            });

            // Error handling is now done server-side with Laravel validation
            // This provides better security and more detailed error messages
        });
    </script>
@endpush
