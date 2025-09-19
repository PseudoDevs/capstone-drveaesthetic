@extends('layouts.app')

@section('title', 'Contact Us | Aesthetic - Dermatology and Skin Care')

@section('contact-active', 'current')

@section('content')


    <!--====================================================================
               Start Banner Section
           =====================================================================-->
    <section style="position: relative; 
           background: url('{{ asset('assets/images/banner/contact_banner.jpg') }}'); 
           background-size: cover; 
           background-position: center;">
        <div class="container">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2 class="page-title">{{ setting('contact.banner_title', 'Contact Us.') }}</h2>
                    <!--<p>{{ setting('contact.banner_description', 'Saunas are used all over the world to improve
                     health to enjoy and relax electronic typesetting.') }}-->
                    </p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="banner-angle">
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
                            <span>{{ setting('contact.address_line_1', '1569  Davis Place,') }}</span>
                            <span>{{ setting('contact.address_line_2', 'Filkon, USA.') }}</span>
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
                <form method="POST" action="#">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="first_name" id="first_name" class="form-control" value=""
                                    required="" placeholder="Name Here">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control" value=""
                                    required="" placeholder="Email Here">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="tel" name="phone-no" id="phone-no" class="form-control" value=""
                                    required="" placeholder="Phone No.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="subject" id="subject" class="form-control" value=""
                                    required="" placeholder="Subject">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-60">
                                <textarea name="message" id="message" class="form-control" rows="7" required="" placeholder="Message"></textarea>
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
