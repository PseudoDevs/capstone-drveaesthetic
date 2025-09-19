@extends('layouts.app')

@section('title', 'Welcome | Aesthetic - Dermatology and Skin Care')

@section('home-active', 'current')

@section('content')


    <!--====================================================================
                                   Start Hero Section
                               =====================================================================-->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="text-white">Extra <span>Care, </span>Extraordinary  <span>Result.</span></h1>
                <h5 class="text-white">Expert cosmetic treatments to rejuvenate your look and boost your confidence. Look as vibrant as you feel.</h5>
                <a href="{{ url('/services') }}" class="theme-btn">Book now</a>
            </div>
        </div>
        <div class="hero-angle">
            <img src="{{ asset('assets/images/hero/hero-angle.png') }}" alt="hero angle">
        </div>
    </section>
    <!--====================================================================
                                   End Hero Section
                               =====================================================================-->


    <!--====================================================================
                                   Start About Section
                               =====================================================================-->
    <section class="about-section my-150 rmy-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="about-images rmb-50">
                        <img src="{{ Storage::url(setting('general.about_image_1')) }}" alt="about image">
                        <img src="{{ Storage::url(setting('general.about_image_2')) }}" alt="about image">
                        <img src="{{ Storage::url(setting('general.about_image_3')) }}" alt="about image">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-content">
                        <div class="section-title">
                            <h2>{{ setting('general.about_title', 'We Have 25+ Years Experience.') }}</h2>
                        </div>
                        <h6>{{ setting('general.about_description', 'Saunas are used all over the world to improve health enjoy relax. During the clients stay in sauna, body is sweating and from harmful substances and toxins.') }}</h6>
                        <p>{{ setting('general.about_content', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                        <div class="vision-mision">
                            <div class="vm-image">
                                <img src="{{ asset('assets/images/about/icon.png') }}" alt="Icon">
                            </div>
                            <div class="vm-content">
                                <h4>Where Art Meets Science</h4>
                                <p>We believe aesthetic medicine is a delicate balance between precise medical science and intuitive artistry. 
                                        Our philosophy is to enhance your natural features, not alter them, achieving results that are both transformative and authentically you.</p>
                            </div>
                        </div>
                        <a href={{ url('/about') }} class="theme-btn style-two">About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====================================================================
                                   End About Section
                               =====================================================================-->


    <!--====================================================================
                                   Start Services Section
                               =====================================================================-->
    <section class="services-section pt-150 rpt-95 mb-145 rmb-95">
        <div class="container">
            <div class="section-title text-center mb-95">
                <h2>Services We Offer</h2>
                <p>It has different attractions tropical rain fog dew wall jets and it is <br> combined with sound,
                    caribbian storm.</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-one">
                        <i class="flaticon-eyepatch"></i>
                        <h4><a href={{ url('/services') }}>Facial Implants</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-two">
                        <i class="flaticon-aesthetic"></i>
                        <h4><a href={{ url('/services') }}>Lip Augmentation</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-three">
                        <i class="flaticon-beauty"></i>
                        <h4><a href={{ url('/services') }}>Blepharoplasty</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-four">
                        <i class="flaticon-ear"></i>
                        <h4><a href={{ url('/services') }}>Ear Surgery</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="service-angle">
            <img src="{{ asset('assets/images/services/service-angle.png') }}" alt="hero angle">
        </div>
        <div class="service-ellipse">
            <img src="{{ asset('assets/images/services/ellipse.png') }}" alt="Ellipse">
        </div>

        <div class="what-experct mt-120 rmt-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div style="height: 100%; 
            min-height: 400px; 
            background-image: url('{{ asset('assets/images/banner/beauty_pic1.png') }}'); 
            background-position: center; 
            background-size: cover;" ></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="what-experct-content">
                            <h3>Enhance Your Beauty, Elevate Your Confidence</h3>
                            <p>Discover a wide range of aesthetic and wellness services designed to help you look radiant
                                 and feel your absolute best.</p>
                            <a href={{ url('/services') }} class="theme-btn style-three">Our Services</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====================================================================
                                   End Services Section
                               =====================================================================-->

    <!--====================================================================
                                   Start Beauty Section
                               =====================================================================-->
    <section  style="background: url('{{ asset('assets/images/banner/beauty_section.jpg') }}') center/cover;">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5">
                    <div class="beauty-content rmb-50 mr-25 rmr-0">
                        <h3>Rediscover Your Radiance.</h3>
                        <p>Enhance your natural beauty with our specialized treatments designed to refresh your skin, 
                            restore confidence, and bring out your healthiest glow.</p>
                        <!--<a href="#" class="theme-btn style-two">Expert Team</a>-->
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="beauty-image ml-30 rml-0">
                        <img src="{{ asset('assets/images/banner/beauty_pic2.png') }}" alt="beauty man">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====================================================================
                                   End Beauty Section
                               =====================================================================-->



    <!--====================================================================
                                   Start Testimonials Section
                               =====================================================================-->
    <section class="testimonial-section bg-three py-135 rpy-95 mb-150">
        <div class="container">
            <div class="testimonial-wrap owl-carousel">
                <div class="testimonial-item">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="testimonial-image">
                                <img src="{{ asset('assets/images/testimonials/sample-face1.png') }}" alt="Testimonial">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="testimonial-content">
                                <h3>Welcome to our clinic, where we don't just treat skin, we care for people.</h3>
                                <p>When I founded Dr Ve Aesthetic Clinic and Wellness Center, it was with a simple but powerful vision: to create a sanctuary where science 
                                    and artistry meet compassion. I was tired of seeing clinics that either felt too clinical and cold or too sales-driven. 
                                    I wanted a place where our patients are treated like family, where we listen first, then guide with absolute honesty and 
                                    medical expertise.</p>
                                <h4>Dr. Ve R. Orcine-Manuel</h4>
                                <span>CLinic Owner</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="testimonial-image">
                                <img src="{{ asset('assets/images/testimonials/sample-face1.png') }}" alt="Testimonial">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="testimonial-content">
                                <h3>Alias dicta iusto sapiente hic. Harum voluptatibus minima officia, labore
                                    deserunt nobis adipisci.</h3>
                                <p>In perferendis delectus a aperiam iste aliquid dolorum nisi porro maiores
                                    asperiores vero eius inventore pariatur sed iure, aut fugiat vitae doloremque
                                    placeat commodi officia accusantium. Inventore dolorum itaque natus, ab expedita
                                    ducimus odit quis Nobis.</p>
                                <h4>Sandra Lyons</h4>
                                <span>Lead beautician</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="testimonial-image">
                                <img src="{{ asset('assets/images/testimonials/sample-face2.jpg') }}" alt="Testimonial">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="testimonial-content">
                                <h3>Doloremque eum eaque, cupiditate sequi est voluptas quibusdam delectus nulla
                                    harum earum.</h3>
                                <p>Reprehenderit, minima consequatur animi nesciunt. Enim quis repellat repellendus
                                    voluptates vero. Saepe laudantium, quas vel, unde in deleniti veniam placeat
                                    repudiandae blanditiis sint dolores error reprehenderit obcaecati.</p>
                                <h4>Edna P. Meza</h4>
                                <span>Lead beautician</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-dotted">
            <img src="{{ asset('assets/images/testimonials/testimonial-dotted.png') }}" alt="dotted">
        </div>
        <div class="testimonial-angle">
            <img src="{{ asset('assets/images/testimonials/testimonial-angle.png') }}" alt="angle">
        </div>
        <div class="testimonial-quote">
            <img src="{{ asset('assets/images/testimonials/quote.png') }}" alt="angle">
        </div>
    </section>
    <!--====================================================================
                                   End Testimonials Section
                               =====================================================================-->



@endsection
