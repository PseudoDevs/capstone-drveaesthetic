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
                <h1 class="text-white">We Are <span>Ready</span> For Your <span>Skin</span> Care <span>Help.</span></h1>
                <h5 class="text-white">Saunas are used all over the world to improve health, to enjoy and relax.</h5>
                <a href="{{ url('/contact') }}" class="theme-btn">Contact now</a>
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
                        <img src="{{ asset('assets/images/about/about1.png') }}" alt="about image">
                        <img src="{{ asset('assets/images/about/about2.png') }}" alt="about image">
                        <img src="{{ asset('assets/images/about/about3.png') }}" alt="about image">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-content">
                        <div class="section-title">
                            <h2>We Have 25+ Years Experience.</h2>
                        </div>
                        <h6>Saunas are used all over the world to improve health enjoy relax. During the clients
                            stay in sauna, body is sweating and from harmful substances and toxins.</h6>
                        <p>It has different attractions – tropical rain, fog, dew, wall jets and it is combined with
                            sound, caribbian storm, aroma and various lighting effects, what makes you have an
                            unforgettable filling.</p>
                        <div class="vision-mision">
                            <div class="vm-image">
                                <img src="{{ asset('assets/images/about/icon.png') }}" alt="Icon">
                            </div>
                            <div class="vm-content">
                                <h4>Our Mission & Vission</h4>
                                <p>The pleasant temperature, similar to body temperature, extending beneath client’s
                                    body, frees the body negative tension caused by everyday stress.</p>
                            </div>
                        </div>
                        <a href="#" class="theme-btn style-two">Expert Team</a>
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
                        <h4><a href="services.html">Facial Implants</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-two">
                        <i class="flaticon-aesthetic"></i>
                        <h4><a href="services.html">Lip Augmentation</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-three">
                        <i class="flaticon-beauty"></i>
                        <h4><a href="services.html">Blepharoplasty</a></h4>
                        <p>It has different attractions combined with sound, caribbian storm, aroma and various.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item style-four">
                        <i class="flaticon-ear"></i>
                        <h4><a href="services.html">Ear Surgery</a></h4>
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
                        <div class="what-experct-img rmb-50"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="what-experct-content">
                            <h3>What Experct When You Arrive For Your Appoinment</h3>
                            <p>It has different attractions – tropical rain, fog, dew, wall jets and it is combined
                                with sound, caribbian storm aroma and variou lighting effecs.</p>
                            <a href="#" class="theme-btn style-three">Expert Team</a>
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
    <section class="beauty-section mb-150 rpy-100">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5">
                    <div class="beauty-content rmb-50 mr-25 rmr-0">
                        <h3>All kind of spa and beauty treatments for your body and soul.</h3>
                        <p>It has different attractions tropical rain, fog, dew, wall jets combined with sound
                            caribbian</p>
                        <a href="#" class="theme-btn style-two">Expert Team</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="beauty-image ml-30 rml-0">
                        <img src="{{ asset('assets/images/beauty/beauty-man.png') }}" alt="beauty man">
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
                                <img src="{{ asset('assets/images/testimonials/testimonial1.png') }}" alt="Testimonial">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="testimonial-content">
                                <h3>It has different attractions – tropical rain, fog, dew, wall jets and it is
                                    combined with sound.</h3>
                                <p>It has different attractions – tropical rain, fog, dew, wall jets and it is
                                    combined caribbian storm, aroma and various lighting effects, what makes you an
                                    unforgetta filling. Hydro-massage is used for muscle relaxation and
                                    anti-stiffness with effect on stress relief.</p>
                                <h4>Sophie Harrison</h4>
                                <span>Lead beautician</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="testimonial-image">
                                <img src="{{ asset('assets/images/testimonials/testimonial2.png') }}" alt="Testimonial">
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
                                <img src="{{ asset('assets/images/testimonials/testimonial3.png') }}" alt="Testimonial">
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
