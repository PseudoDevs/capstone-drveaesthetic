@extends('layouts.app')

@section('title', 'Contact Us | Aesthetic - Dermatology and Skin Care')

@section('contact-active', 'current')

@section('content')


    <!--====================================================================
               Start Banner Section
           =====================================================================-->
    <section style="position: relative; 
           background: url('{{ asset('assets/images/banner/contact_banner.png') }}'); 
           background-size: cover; 
           background-position: center;">
        <!-- Black transparent overlay -->
        <div style="position: absolute; 
             top: 0; 
             left: 0; 
             right: 0; 
             bottom: 0; 
             background: rgba(0, 0, 0, 0.5); 
             z-index: 1;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="banner-inner" style="text-align: center;">
                <div class="banner-content">
                    <h2 class="page-title" style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                        <span style="color: #fbaaa9;">Contact</span> <span style="color: white;">Us</span>
                    </h2>
                    <h4 style="font-size: 1.5rem; font-weight: 400; margin-bottom: 1.5rem; color: white;">
                        Get in touch with us for consultations and appointments. We're here to help you on your aesthetic journey.
                    </h4>
                </div>
                <nav aria-label="breadcrumb" style="margin-top: 2rem;">
                    <ol class="breadcrumb" style="justify-content: center;">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: white; text-decoration: none;">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #fbaaa9;">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="banner-angle" style="position: absolute; bottom: 0; right: 0; z-index: 2;">
            <img src="{{ asset('assets/images/banner/banner-angle.png') }}" alt="Angle">
        </div>
    </section>
    <!--====================================================================
               End Banner Section
           =====================================================================-->


    <!--====================================================================
               Start Contact Info Section
           =====================================================================-->
    <div class="contact-info mt-150 rmt-125">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="flaticon-phone-call"></i>
                        </div>
                        <div class="info-content">
                            <span>{{ setting('contact.phone_1', '+00 569 846 358') }}</span>
                            <span>{{ setting('contact.phone_2', '+00 896 387 439') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="flaticon-location"></i>
                        </div>
                        <div class="info-content">
                            <span>{{ setting('contact.address_line_1', 'San Jose,') }}</span>
                            <span>{{ setting('contact.address_line_2', 'Iriga City, Philippines, 4431') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="flaticon-mail"></i>
                        </div>
                        <div class="info-content">
                            <span>{{ setting('contact.email_1', 'support@gmail.com') }}</span>
                            <span>{{ setting('contact.email_2', 'gilkan4@gmail.com') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================================================================
               End Contact Info Section
           =====================================================================-->



    <!--====================================================================
               Start Contact Form Section
           =====================================================================-->
    <div class="contact-form">
        <div class="container">
            <div class="contact-form-inner">
                <div class="section-title text-center mb-95">
                    <h2>{{ setting('contact.form_title', 'Get In Touch') }}</h2>
                    <p>{{ setting('contact.form_description', 'It has different attractions tropical rain fog dew wall jets and it is combined with sound, caribbian storm.') }}
                    </p>
                </div>
                @if(session('success'))
                    <div class="alert alert-success mb-4 text-center">
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}"
                                    required="" placeholder="Name Here">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                                    required="" placeholder="Email Here">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="tel" name="phone-no" id="phone-no" class="form-control" value="{{ old('phone-no') }}"
                                    required="" placeholder="Phone No.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}"
                                    required="" placeholder="Subject">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-60">
                                <textarea name="message" id="message" class="form-control" rows="7" required="" placeholder="Message">{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="theme-btn style-two">Send Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--====================================================================
               End Contact Form Section
           =====================================================================-->



    <!--====================================================================
               Start Contact Map Section
           =====================================================================-->
    <div class="contact-map" id="map"></div>
    <!--====================================================================
               End Contact Map Section
           =====================================================================-->


@endsection
