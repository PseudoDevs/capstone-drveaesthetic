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
    <link rel="shortcut icon" href="https://scontent.fmnl4-7.fna.fbcdn.net/v/t39.30808-6/418729090_122097326798182940_868500779979598848_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeExtMuvkhE4ITBCXKkbJRRmnZbZoGt7CtWdltmga3sK1V49cOQhA3jFasNBp_355lXq9Z0SxpMfYO43nSvwjgEr&_nc_ohc=La3mCVpYfvoQ7kNvwHBYzhO&_nc_oc=Adnnq_zkxQC7XdIas186Yx9ZgMLxcI1XeTJYpdgKK4mhe7N5JfS-nAYShGVX5jKZxfipaj9oPf8OcvMoWsUBWgXb&_nc_zt=23&_nc_ht=scontent.fmnl4-7.fna&_nc_gid=BRpEQPw1c2GJuhnccL-_dQ&oh=00_AfcL8Mtx-BdH9ttmonHPkj0ErnW758ryC_w_EGRL27wU8Q&oe=68F2EE14" type="image/x-icon">
    <link rel="icon" href="https://scontent.fmnl4-7.fna.fbcdn.net/v/t39.30808-6/418729090_122097326798182940_868500779979598848_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeExtMuvkhE4ITBCXKkbJRRmnZbZoGt7CtWdltmga3sK1V49cOQhA3jFasNBp_355lXq9Z0SxpMfYO43nSvwjgEr&_nc_ohc=La3mCVpYfvoQ7kNvwHBYzhO&_nc_oc=Adnnq_zkxQC7XdIas186Yx9ZgMLxcI1XeTJYpdgKK4mhe7N5JfS-nAYShGVX5jKZxfipaj9oPf8OcvMoWsUBWgXb&_nc_zt=23&_nc_ht=scontent.fmnl4-7.fna&_nc_gid=BRpEQPw1c2GJuhnccL-_dQ&oh=00_AfcL8Mtx-BdH9ttmonHPkj0ErnW758ryC_w_EGRL27wU8Q&oe=68F2EE14" type="image/x-icon">
    <link rel="apple-touch-icon" href="https://scontent.fmnl4-7.fna.fbcdn.net/v/t39.30808-6/418729090_122097326798182940_868500779979598848_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeExtMuvkhE4ITBCXKkbJRRmnZbZoGt7CtWdltmga3sK1V49cOQhA3jFasNBp_355lXq9Z0SxpMfYO43nSvwjgEr&_nc_ohc=La3mCVpYfvoQ7kNvwHBYzhO&_nc_oc=Adnnq_zkxQC7XdIas186Yx9ZgMLxcI1XeTJYpdgKK4mhe7N5JfS-nAYShGVX5jKZxfipaj9oPf8OcvMoWsUBWgXb&_nc_zt=23&_nc_ht=scontent.fmnl4-7.fna&_nc_gid=BRpEQPw1c2GJuhnccL-_dQ&oh=00_AfcL8Mtx-BdH9ttmonHPkj0ErnW758ryC_w_EGRL27wU8Q&oe=68F2EE14">

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
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
            text-align: left;
            line-height: 1.2;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0 0 2px 0;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            transition: all 0.3s ease;
        }

        .clinic-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #666;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            transition: all 0.3s ease;
        }

        .text-logo:hover .clinic-name {
            color: #fbaaa9;
            transform: scale(1.02);
        }

        .text-logo:hover .clinic-subtitle {
            color: #fbaaa9;
        }

        @media (max-width: 768px) {
            .clinic-name {
                font-size: 20px;
            }
            
            .clinic-subtitle {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .clinic-name {
                font-size: 18px;
            }
            
            .clinic-subtitle {
                font-size: 11px;
            }
        }

        /* Mobile Navigation Positioning */
        @media (max-width: 768px) {
            .header-inner {
                justify-content: space-between;
                align-items: center;
            }
            
            .nav-outer {
                margin-left: 0 !important;
                order: 2;
            }
            
            .logo-outer {
                order: 1;
                flex: 1;
            }
            
            /* Position navbar toggle to the right */
            .navbar-header {
                float: right;
                margin-left: auto;
            }
            
            .navbar-toggle {
                float: right;
                margin-left: auto;
                padding: 8px 12px;
                background: transparent;
                border: none;
                cursor: pointer;
            }
            
            /* Mobile menu positioning */
            .navbar-collapse {
                position: absolute;
                top: 100%;
                right: 0;
                left: auto;
                width: 280px;
                background: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                border-radius: 8px;
                z-index: 1000;
                padding: 15px 0;
            }
            
            .navbar-collapse .navigation {
                flex-direction: column;
                width: 100%;
                padding: 0 15px;
            }
            
            .navbar-collapse .navigation li {
                width: 100%;
                margin: 0;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .navbar-collapse .navigation li:last-child {
                border-bottom: none;
            }
            
            .navbar-collapse .navigation li a {
                display: block;
                padding: 12px 15px;
                color: #333;
                text-decoration: none;
                transition: all 0.3s ease;
                border-radius: 6px;
                margin: 2px 0;
            }
            
            .navbar-collapse .navigation li a:hover {
                background: #f8f9fa;
                color: #007bff;
                transform: translateX(5px);
            }
            
            /* Dropdown positioning for mobile */
            .navbar-collapse .dropdown-menu {
                position: static;
                float: none;
                width: 100%;
                margin-top: 0;
                background-color: #f8f9fa;
                border: none;
                box-shadow: none;
                border-radius: 6px;
                margin: 5px 0;
            }
            
            .navbar-collapse .dropdown-menu .dropdown-item {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-collapse {
                width: 260px;
            }
            
            .navbar-toggle {
                padding: 6px 10px;
            }
        }

        /* Mobile Sidebar Styles */
        .mobile-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -320px;
            width: 320px;
            height: 100vh;
            background: white;
            z-index: 1050;
            transition: left 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        
        .mobile-sidebar.show {
            left: 0;
        }
        
        .mobile-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #fbaaa9, #ff9a9e);
            color: white;
        }
        
        .mobile-sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        
        .mobile-sidebar-logo .text-logo {
            text-align: center;
            width: 100%;
        }
        
        .mobile-sidebar-logo .clinic-name {
            font-size: 18px;
            font-weight: 700;
            color: white;
            margin: 0 0 2px 0;
            line-height: 1.1;
            font-family: 'Playfair Display', serif;
            font-style: italic;
        }
        
        .mobile-sidebar-logo .clinic-subtitle {
            font-size: 12px;
            font-weight: 400;
            color: white;
            margin: 0;
            line-height: 1.2;
            font-family: 'Roboto', sans-serif;
        }
        
        .mobile-sidebar-close {
            background: none;
            border: none;
            font-size: 20px;
            color: white;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .mobile-sidebar-close:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .mobile-sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        .mobile-sidebar-section {
            margin-bottom: 30px;
        }
        
        .mobile-sidebar-section-title {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 15px 20px;
            padding: 0;
        }
        
        .mobile-sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .mobile-sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
        }
        
        .mobile-sidebar-item:hover {
            background: #f8f9fa;
            border-left-color: #fbaaa9;
        }
        
        .mobile-sidebar-item.active {
            background: #e3f2fd;
            border-left-color: #0088cc;
            color: #0088cc;
        }
        
        .mobile-sidebar-item i {
            width: 20px;
            font-size: 16px;
            margin-right: 12px;
            text-align: center;
        }
        
        .mobile-sidebar-item span {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }
        
        .mobile-sidebar-link {
            display: flex;
            align-items: center;
            width: 100%;
            text-decoration: none;
            color: inherit;
        }
        
        .mobile-sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e5e5e5;
            background: #f8f9fa;
        }
        
        .mobile-sidebar-user {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .mobile-sidebar-avatar {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }
        
        .mobile-sidebar-avatar img,
        .mobile-sidebar-avatar .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        
        .mobile-sidebar-avatar .avatar-placeholder {
            background: linear-gradient(135deg, #fbaaa9, #ff9a9e);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        
        .mobile-sidebar-user-info h5 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 2px 0;
            color: #333;
        }
        
        .mobile-sidebar-user-info p {
            font-size: 12px;
            color: #666;
            margin: 0;
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

        /* Clinic Logo Styles - Mobile App Style Integration */
        .clinic-logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clinic-logo-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .clinic-logo-img:hover {
            transform: scale(1.05);
        }

        /* Clean, minimal logo container */
        .logo {
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: translateY(-1px);
        }

        /* Mobile responsive adjustments - Clean mobile app style */
        @media (max-width: 768px) {
            .clinic-logo-img {
                width: 40px;
                height: 40px;
                border-radius: 6px;
            }
            
            .clinic-logo {
                margin-right: 10px !important;
            }
        }

        @media (max-width: 480px) {
            .clinic-logo-img {
                width: 35px;
                height: 35px;
                border-radius: 5px;
            }
            
            .clinic-logo {
                margin-right: 8px !important;
            }
        }

        /* Mobile Sidebar Logo Styles - Clean Mobile App Style */
        .mobile-clinic-logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-clinic-logo-img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .mobile-sidebar-logo .text-logo {
            text-align: left;
            flex: 1;
        }

        .mobile-sidebar-logo .clinic-name {
            font-size: 14px;
            font-weight: 700;
            color: white;
            margin: 0 0 2px 0;
            line-height: 1.1;
            font-family: 'Playfair Display', serif;
            font-style: italic;
        }

        .mobile-sidebar-logo .clinic-subtitle {
            font-size: 9px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            line-height: 1.2;
            font-family: 'Roboto', sans-serif;
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
                            <div class="logo d-flex align-items-center">
                                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                                    <div class="clinic-logo me-2">
                                        <img src="{{ asset('assets/images/new-logo-bk.png') }}"
                                             alt="Dr. Ve Aesthetic Clinic Logo"
                                             class="clinic-logo-img">
                                    </div>
                                    <div class="text-logo">
                                        <h2 class="clinic-name">Dr. Ve Aesthetic Clinic</h2>
                                        <p class="clinic-subtitle">and Wellness Center</p>
                                    </div>
                                </a>
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
                                                                class="fas fa-dice-d6 mr-2"></i>Dashboard</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('users.billing-dashboard') }}"><i
                                                                class="fas fa-credit-card mr-2"></i>Billing Dashboard</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('users.profile.edit') }}"><i
                                                                class="fas fa-user mr-2"></i>Edit Profile</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('chat.index') }}"><i
                                                                class="fas fa-comments mr-2"></i>Chat</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('feedback.create') }}"><i
                                                                class="fas fa-star mr-2"></i>Leave Feedback</a></li>
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

        <!-- Mobile Sidebar Navigation -->
        @auth
        <div class="mobile-sidebar-overlay d-md-none" id="mobileSidebarOverlay"></div>
        <div class="mobile-sidebar d-md-none" id="mobileSidebar">
            <div class="mobile-sidebar-header">
                <div class="mobile-sidebar-logo">
                    <div class="d-flex align-items-center">
                        <div class="mobile-clinic-logo me-2">
                            <img src="https://scontent.fmnl4-7.fna.fbcdn.net/v/t39.30808-6/418729090_122097326798182940_868500779979598848_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeExtMuvkhE4ITBCXKkbJRRmnZbZoGt7CtWdltmga3sK1V49cOQhA3jFasNBp_355lXq9Z0SxpMfYO43nSvwjgEr&_nc_ohc=La3mCVpYfvoQ7kNvwHBYzhO&_nc_oc=Adnnq_zkxQC7XdIas186Yx9ZgMLxcI1XeTJYpdgKK4mhe7N5JfS-nAYShGVX5jKZxfipaj9oPf8OcvMoWsUBWgXb&_nc_zt=23&_nc_ht=scontent.fmnl4-7.fna&_nc_gid=BRpEQPw1c2GJuhnccL-_dQ&oh=00_AfcL8Mtx-BdH9ttmonHPkj0ErnW758ryC_w_EGRL27wU8Q&oe=68F2EE14" 
                             alt="Dr. Ve Aesthetic Clinic Logo" 
                             class="mobile-clinic-logo-img">
                        </div>
                        <div class="text-logo">
                            <h2 class="clinic-name">Dr. Ve Aesthetic Clinic</h2>
                            <p class="clinic-subtitle">and Wellness Center</p>
                        </div>
                    </div>
                </div>
                <button class="mobile-sidebar-close" id="mobileSidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-sidebar-content">
                <div class="mobile-sidebar-section">
                    <h4 class="mobile-sidebar-section-title">Navigation</h4>
                    <ul class="mobile-sidebar-nav">
                        <li class="mobile-sidebar-item">
                            <a href="{{ url('/') }}" class="mobile-sidebar-link">
                                <i class="fas fa-home"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ url('/about') }}" class="mobile-sidebar-link">
                                <i class="fas fa-info-circle"></i>
                                <span>About</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ url('/services') }}" class="mobile-sidebar-link">
                                <i class="fas fa-spa"></i>
                                <span>Services</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ url('/contact') }}" class="mobile-sidebar-link">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="mobile-sidebar-section">
                    <h4 class="mobile-sidebar-section-title">My Account</h4>
                    <ul class="mobile-sidebar-nav">
                        <li class="mobile-sidebar-item">
                            <a href="{{ url('/users/dashboard') }}" class="mobile-sidebar-link">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ route('users.billing-dashboard') }}" class="mobile-sidebar-link">
                                <i class="fas fa-credit-card"></i>
                                <span>Billing Dashboard</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ route('users.profile.edit') }}" class="mobile-sidebar-link">
                                <i class="fas fa-user-edit"></i>
                                <span>Edit Profile</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ route('chat.index') }}" class="mobile-sidebar-link">
                                <i class="fas fa-comments"></i>
                                <span>Chat</span>
                            </a>
                        </li>
                        <li class="mobile-sidebar-item">
                            <a href="{{ route('feedback.create') }}" class="mobile-sidebar-link">
                                <i class="fas fa-star"></i>
                                <span>Feedback</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mobile-sidebar-footer">
                <div class="mobile-sidebar-user">
                    <div class="mobile-sidebar-avatar">
                        @if (auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="mobile-sidebar-user-info">
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mobile-sidebar-logout">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth

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
                                        @php
                                            $categories = \App\Models\Category::withCount('clinicServices')->whereHas('clinicServices', function($query) {
                                                $query->where('status', 'active');
                                            })->take(7)->get();
                                        @endphp
                                        @foreach($categories as $category)
                                            <li><a href="{{ route('services') }}">{{ $category->category_name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
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

    <!-- Mobile Sidebar JavaScript -->
    <script>
        $(document).ready(function() {
            // Mobile Sidebar Functions
            const sidebar = $('#mobileSidebar');
            const overlay = $('#mobileSidebarOverlay');
            const navbarToggle = $('.navbar-toggle');
            const closeBtn = $('#mobileSidebarClose');
            
            // Override navbar toggle behavior on mobile
            navbarToggle.on('click', function(e) {
                if ($(window).width() <= 768) {
                    // Check if user is authenticated and mobile sidebar exists
                    if (sidebar.length > 0) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Toggle mobile sidebar instead of default navbar
                        if (sidebar.hasClass('show')) {
                            closeSidebar();
                        } else {
                            openSidebar();
                        }
                    }
                    // If not authenticated, let the default navbar behavior work
                }
            });
            
            // Open sidebar
            function openSidebar() {
                if (sidebar.length > 0) {
                    sidebar.addClass('show');
                    overlay.addClass('show');
                    $('body').addClass('sidebar-open').css('overflow', 'hidden');
                }
            }
            
            // Close sidebar
            function closeSidebar() {
                if (sidebar.length > 0) {
                    sidebar.removeClass('show');
                    overlay.removeClass('show');
                    $('body').removeClass('sidebar-open').css('overflow', '');
                }
            }
            
            // Close sidebar handlers (only if sidebar exists)
            if (closeBtn.length > 0) {
                closeBtn.on('click', closeSidebar);
            }
            if (overlay.length > 0) {
                overlay.on('click', closeSidebar);
            }
            
            // Handle window resize
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>
