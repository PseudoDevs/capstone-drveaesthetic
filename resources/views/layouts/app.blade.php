<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <!-- Responsive Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Capstone Aesthetic - Dermatology and Skin Care')</title>
    <!-- Fav Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spacing.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    @stack('styles')

    <!-- Custom Logo Styles -->
    <style>
        .text-logo {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            letter-spacing: 1px;
        }

        .text-logo:hover {
            color: #007bff;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .text-logo {
                font-size: 20px;
            }
        }

        .avatar-placeholder-small {
            width: 40px;
            height: 40px;
        }

        .avatar-placeholder-large {
            width: 150px;
            height: 150px;
            font-size: 3rem;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <!-- Preloader -->
        <div class="preloader"></div>

        <!--====================================================================
                                Start Header area
        =====================================================================-->
        <header class="main-header">
            <!--Header-Upper-->
            <div class="header-upper">
                <div class="container clearfix">

                    <div class="header-inner d-lg-flex align-items-center">

                        <div class="logo-outer d-flex align-items-center">
                            <div class="logo"><a href="{{ url('/') }}"
                                    class="text-logo">{{ setting('general.site_name', env('APP_NAME', 'Capstone Aesthetic')) }}</a>
                            </div>
                        </div>

                        <div class="nav-outer clearfix ml-lg-auto">
                            <!-- Main Menu -->
                            <nav class="main-menu navbar-expand-lg">
                                <div class="navbar-header clearfix">
                                    <!-- Toggle Button -->
                                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                                        data-target=".navbar-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="navbar-collapse collapse clearfix">
                                    <ul class="navigation clearfix">
                                        <li class="@yield('home-active')"><a href="{{ url('/') }}">Home</a></li>
                                        <li class="@yield('about-active')"><a href="{{ url('/about') }}">About</a></li>
                                        <li class="@yield('services-active')"><a href="{{ url('/services') }}">Services</a>
                                        </li>
                                        <li class="@yield('contact-active')"><a href="{{ url('/contact') }}">Contact</a></li>
                                        @auth
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    @if (auth()->user()->avatar_url)
                                                        <img src="{{ auth()->user()->avatar_url }}"
                                                            alt="{{ auth()->user()->name }}" class="rounded-circle mr-2"
                                                            width="30" height="30">
                                                    @endif
                                                    My Account
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right shadow">
                                                    <li class="dropdown-header bg-light">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                @if (auth()->user()->avatar_url)
                                                                    <img src="{{ auth()->user()->avatar_url }}"
                                                                        alt="{{ auth()->user()->name }}"
                                                                        class="rounded-circle" width="40"
                                                                        height="40">
                                                                @else
                                                                    <div
                                                                        class="avatar-placeholder-small rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold">
                                                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 font-weight-bold">
                                                                    {{ auth()->user()->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ auth()->user()->email }}</small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <div class="dropdown-divider"></div>
                                                    <li><a class="dropdown-item" href="{{ url('/users/dashboard') }}"><i
                                                                class="fa fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('users.profile.edit') }}"><i
                                                                class="fa fa-user mr-2"></i>Edit Profile</a></li>
                                                    <div class="dropdown-divider"></div>
                                                    <li>
                                                        <form method="POST" action="{{ route('logout') }}"
                                                            class="m-0">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa fa-sign-out-alt mr-2"></i>Logout
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li><a href="{{ url('/users/login') }}">Login</a></li>
                                        @endauth
                                    </ul>
                                </div>

                            </nav>
                            <!-- Main Menu End-->
                        </div>
                    </div>

                </div>
            </div>
            <!--End Header Upper-->

        </header>
        <!--====================================================================
                                End Header area
        =====================================================================-->

        @yield('content')

        <!--====================================================================
                                Start Footer Section
        =====================================================================-->
        <footer class="footer-section pt-150 rpt-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="widget about-widget mr-30 rmr-0">
                                    <h4 class="footer-title">{{ setting('footer.about_title', 'About Us.') }}</h4>
                                    <div class="about-widget-content">
                                        <p>{{ setting('footer.about_description', 'The pleasant temperature, similar body temperature, extending beneath client\'s body, frees the body negative tension caused by everyday stress.') }}
                                        </p>
                                        <div class="about-widget-contact mt-25">
                                            <p>{{ setting('footer.contact_address_line_1', '176 W street name,') }}
                                                <br>{{ setting('footer.contact_address_line_2', 'New York, NY 10014') }}
                                            </p>
                                            <h4><a
                                                    href="callto:{{ str_replace(' ', '', setting('footer.contact_phone', '+00 568 468 349')) }}">{{ setting('footer.contact_phone', '+00 568 468 349') }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget services-widget">
                                    <h4 class="footer-title">{{ setting('footer.services_title', 'Our Services.') }}
                                    </h4>
                                    <ul class="list-style-one">
                                        @foreach (setting('footer.services_list', [['name' => 'Breast Augmentation', 'url' => '#'], ['name' => 'Mommy Makeover', 'url' => '#'], ['name' => 'Eyelid Surgery', 'url' => '#'], ['name' => 'Skin Care Treatments', 'url' => '#'], ['name' => 'Useful Links', 'url' => '#'], ['name' => 'Free Consultation', 'url' => '#'], ['name' => 'Customer Support', 'url' => '#']]) as $service)
                                            <li><a
                                                    href="{{ $service['url'] ?? '#' }}">{{ $service['name'] ?? '' }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="widget subscribe-widget">
                            <h4 class="footer-title">{{ setting('footer.subscribe_title', 'Subscribe Now.') }}</h4>
                            <form action="#">
                                <input type="email"
                                    placeholder="{{ setting('footer.subscribe_placeholder', 'Your email here') }}"
                                    required>
                                <div class="btn-and-text">
                                    <button type="submit"><i class="flaticon-right-arrow-1"></i></button>
                                    <p>{{ setting('footer.subscribe_description', 'Receive weekly tips & tricks on beauty.') }}
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright Area-->
            <div class="copyright mt-100 rmt-50">
                <div class="container">
                    <div class="copyright-inner">
                        <p>{{ setting('footer.copyright_text', 'Copyright@2025 ' . env('APP_NAME') . '. All rights reserved') }}
                        </p>
                        <!-- Scroll Top Button -->
                        <button class="scroll-top scroll-to-target" data-target="html"><span
                                class="flaticon-up-arrow"></span></button>
                    </div>
                </div>
            </div>

            <div class="footer-dotted-top">
                <img src="{{ asset('assets/images/footer/footer-dot1.png') }}" alt="Footer Dotted">
            </div>
            <div class="footer-dotted-bottom">
                <img src="{{ asset('assets/images/footer/footer-dot2.png') }}" alt="Footer Dotted">
            </div>
        </footer>

        <!--====================================================================
                                End Footer Section
        =====================================================================-->

    </div>
    <!--End pagewrapper-->


    <!-- jequery plugins -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/parallax.min.js') }}"></script>
    <script src="{{ asset('assets/js/leaflet.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.js') }}"></script>

    <!-- Custom script -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    @stack('scripts')

</body>

</html>
