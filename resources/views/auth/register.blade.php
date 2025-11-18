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
                        <!-- Floating Elements -->
                        <div class="floating-elements">
                            <div class="floating-element"></div>
                            <div class="floating-element"></div>
                            <div class="floating-element"></div>
                        </div>

                        <!-- Clinic Logo -->
                        <div class="clinic-logo">
                            <h1>Dr. Ve Aesthetic</h1>
                            <div class="tagline">Where Beauty Meets Excellence</div>
                        </div>

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
            background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 25%, #fecfef 50%, #fecfef 75%, #fbaaa9 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-form-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .clinic-logo {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .clinic-logo h1 {
            color: #fbaaa9;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .clinic-logo .tagline {
            color: #666;
            font-size: 14px;
            margin-top: 0;
            font-style: italic;
            display: block;
        }

        .section-title .sub-title {
            color: #fbaaa9;
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
            background: linear-gradient(135deg, #333 0%, #fbaaa9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            height: 55px;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            border-color: #fbaaa9;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(251, 170, 169, 0.25);
            transform: translateY(-2px);
            background: white;
        }

        .form-control:hover {
            border-color: #fbaaa9;
            transform: translateY(-1px);
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
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .theme-btn {
            background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%);
            color: white;
            border: none;
            height: 55px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .theme-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(251, 170, 169, 0.4);
        }

        .theme-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .theme-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
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
            height: 55px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .google-login-btn:hover {
            border-color: #fbaaa9;
            background: white;
            color: #333;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 30%;
            right: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
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
