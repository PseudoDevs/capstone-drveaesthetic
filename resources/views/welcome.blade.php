@extends('layouts.app')

@section('title', 'Welcome | Aesthetic - Dermatology and Skin Care')

@section('home-active', 'current')

@section('content')

<style>
    /* Enhanced Services Section Styles */
    .service-item:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 20px 40px rgba(251, 170, 169, 0.2) !important;
    }
    
    .service-item:hover .service-hover-bg {
        opacity: 1 !important;
    }
    
    .service-item:hover .read-more {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(251, 170, 169, 0.4) !important;
    }
    
    .theme-btn:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(251, 170, 169, 0.4) !important;
    }
    
    .theme-btn:hover div {
        left: 100% !important;
    }
    
    .service-icon img {
        transition: transform 0.3s ease !important;
    }
    
    .service-item:hover .service-icon img {
        transform: scale(1.1) !important;
    }
    
    /* Feedback Section Styles */
    .feedback-card:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }
    
    .feedback-card:hover .feedback-hover-bg {
        opacity: 1 !important;
    }
</style>


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
            <img src="{{ asset('assets/images/hero/hero-angle.png') }}" alt="Hero Angle">
        </div>
    </section>
    <!--====================================================================
                                   End Hero Section
                               =====================================================================-->



    <!--====================================================================
                                   Start About Section
                               =====================================================================-->
    <section class="about-section pt-150 rpt-100 pb-150 rpb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-images">
                        @if(setting('general.about_image_1'))
                            <img src="{{ Storage::url(setting('general.about_image_1')) }}" alt="About Image 1">
                        @else
                            <img src="{{ asset('assets/images/about/about1.png') }}" alt="about image">
                        @endif
                        
                        @if(setting('general.about_image_2'))
                            <img src="{{ Storage::url(setting('general.about_image_2')) }}" alt="About Image 2">
                        @else
                            <img src="{{ asset('assets/images/about/about2.png') }}" alt="about image">
                        @endif
                        
                        @if(setting('general.about_image_3'))
                            <img src="{{ Storage::url(setting('general.about_image_3')) }}" alt="About Image 3">
                        @else
                            <img src="{{ asset('assets/images/about/about3.png') }}" alt="about image">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
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
    <section class="services-section pt-150 rpt-100 pb-150 rpb-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <div class="section-title">
                        <span class="sub-title mb-15" style="color: #fbaaa9; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Our Services</span>
                        <h2 style="color: #333; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">{{ setting('general.services_title', 'We Provide Best Services') }}</h2>
                        <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">{{ setting('general.services_description', 'We offer a comprehensive range of aesthetic and dermatological treatments designed to enhance your natural beauty and improve your skin health.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @php
                    $categories = \App\Models\Category::withCount('clinicServices')->whereHas('clinicServices', function($query) {
                        $query->where('status', 'active');
                    })->get();
                @endphp
                @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-item" style="background: white; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border: 1px solid rgba(251, 170, 169, 0.1); height: 100%; position: relative; overflow: hidden;">
                        <div class="service-icon" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; position: relative; z-index: 2;">
                            @php
                                $categoryIcons = [
                                    'Dermatology' => 'fas fa-user-md',
                                    'Cosmetic Surgery' => 'fas fa-cut',
                                    'Laser Treatment' => 'fas fa-bolt',
                                    'Skin Care' => 'fas fa-leaf',
                                    'Anti-Aging' => 'fas fa-clock',
                                    'Hair Treatment' => 'fas fa-cut',
                                    'Body Contouring' => 'fas fa-dumbbell',
                                    'Facial Treatment' => 'fas fa-smile',
                                    'Wellness' => 'fas fa-heart',
                                    'Consultation' => 'fas fa-comments'
                                ];
                                $icon = $categoryIcons[$category->category_name] ?? 'fas fa-spa';
                            @endphp
                            <i class="{{ $icon }}" style="color: white; font-size: 2rem;"></i>
                        </div>
                        
                        <div class="service-content" style="position: relative; z-index: 2;">
                            <h4 style="color: #333; font-size: 1.3rem; font-weight: 600; margin-bottom: 15px;">
                                <a href="{{ route('services') }}" style="color: #333; text-decoration: none;">{{ $category->category_name }}</a>
                            </h4>
                            
                            <div class="service-count" style="background: rgba(251, 170, 169, 0.1); color: #fbaaa9; padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: 500; margin-bottom: 15px; display: inline-block;">
                                {{ $category->clinic_services_count }} {{ $category->clinic_services_count == 1 ? 'Service' : 'Services' }}
                            </div>
                            
                            <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                                @if($category->description)
                                    {{ Str::limit($category->description, 120) }}
                                @else
                                    Explore our comprehensive range of {{ strtolower($category->category_name) }} treatments designed to enhance your natural beauty.
                                @endif
                            </p>
                            
                            
                        </div>
                        
                        <!-- Hover Effect Background -->
                        <div class="service-hover-bg" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(251, 170, 169, 0.05) 0%, rgba(255, 154, 158, 0.05) 100%); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('services') }}" class="theme-btn" style="background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); color: white; border: none; padding: 15px 40px; border-radius: 30px; font-size: 1.1rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; position: relative; overflow: hidden;">
                    <span style="position: relative; z-index: 2;">View All Services</span>
                    <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); transition: left 0.5s;"></div>
                </a>
            </div>
        </div>
    </section>
    <!--====================================================================
                                   End Services Section
                               =====================================================================-->



    <!--====================================================================
                                   Start Customer Feedback Section
                               =====================================================================-->
    <section class="feedback-section pt-150 rpt-100 pb-150 rpb-100" style="background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); color: white;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <div class="section-title">
                        <span class="sub-title mb-15" style="color: rgba(255, 255, 255, 0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Customer Reviews</span>
                        <h2 style="color: white; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">What Our Clients Say</h2>
                        <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; line-height: 1.6;">Real experiences from our satisfied customers who have transformed their skin and confidence.</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @php
                    $feedbacks = \App\Models\Feedback::with('client')->orderBy('created_at', 'desc')->take(3)->get();
                @endphp
                
                @if($feedbacks->count() > 0)
                    @foreach($feedbacks as $feedback)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feedback-card" style="background: rgba(255, 255, 255, 0.95); color: #333; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden;">
                            <div class="feedback-rating" style="margin-bottom: 15px;">
                                @for($i = 1; $i <= $feedback->rating; $i++)
                                    <i class="fas fa-star" style="color: #ffc107; font-size: 1.2rem; margin-right: 2px;"></i>
                                @endfor
                                @for($i = $feedback->rating + 1; $i <= 5; $i++)
                                    <i class="far fa-star" style="color: #ddd; font-size: 1.2rem; margin-right: 2px;"></i>
                                @endfor
                            </div>
                            
                            <div class="feedback-content" style="margin-bottom: 20px;">
                                <p style="color: #666; line-height: 1.6; font-style: italic; margin-bottom: 20px;">
                                    "{{ Str::limit($feedback->comment, 150) }}"
                                </p>
                            </div>
                            
                            <div class="feedback-author" style="border-top: 1px solid #e1e8ed; padding-top: 20px;">
                                <div class="author-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                    <i class="fas fa-user" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px;">
                                    {{ $feedback->client ? $feedback->client->name : 'Anonymous' }}
                                </h5>
                                <p style="color: #999; font-size: 0.9rem; margin-bottom: 0;">
                                    {{ $feedback->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            
                            <!-- Hover Effect Background -->
                            <div class="feedback-hover-bg" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(251, 170, 169, 0.05) 0%, rgba(255, 154, 158, 0.05) 100%); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Default testimonials if no feedback exists -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feedback-card" style="background: rgba(255, 255, 255, 0.95); color: #333; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden;">
                            <div class="feedback-rating" style="margin-bottom: 15px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: #ffc107; font-size: 1.2rem; margin-right: 2px;"></i>
                                @endfor
                            </div>
                            <div class="feedback-content" style="margin-bottom: 20px;">
                                <p style="color: #666; line-height: 1.6; font-style: italic; margin-bottom: 20px;">
                                    "The facial treatment was absolutely amazing! My skin has never looked better. The staff is professional and the results exceeded my expectations."
                                </p>
                            </div>
                            <div class="feedback-author" style="border-top: 1px solid #e1e8ed; padding-top: 20px;">
                                <div class="author-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                    <i class="fas fa-user" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px;">Sarah M.</h5>
                                <p style="color: #999; font-size: 0.9rem; margin-bottom: 0;">Facial Treatment Client</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feedback-card" style="background: rgba(255, 255, 255, 0.95); color: #333; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden;">
                            <div class="feedback-rating" style="margin-bottom: 15px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: #ffc107; font-size: 1.2rem; margin-right: 2px;"></i>
                                @endfor
                            </div>
                            <div class="feedback-content" style="margin-bottom: 20px;">
                                <p style="color: #666; line-height: 1.6; font-style: italic; margin-bottom: 20px;">
                                    "Dr. Ve Aesthetic transformed my confidence with their laser treatments. The results are incredible and the staff made me feel comfortable throughout the process."
                                </p>
                            </div>
                            <div class="feedback-author" style="border-top: 1px solid #e1e8ed; padding-top: 20px;">
                                <div class="author-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                    <i class="fas fa-user" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px;">Maria L.</h5>
                                <p style="color: #999; font-size: 0.9rem; margin-bottom: 0;">Laser Treatment Client</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feedback-card" style="background: rgba(255, 255, 255, 0.95); color: #333; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden;">
                            <div class="feedback-rating" style="margin-bottom: 15px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: #ffc107; font-size: 1.2rem; margin-right: 2px;"></i>
                                @endfor
                            </div>
                            <div class="feedback-content" style="margin-bottom: 20px;">
                                <p style="color: #666; line-height: 1.6; font-style: italic; margin-bottom: 20px;">
                                    "Professional service and amazing results! The anti-aging treatments have made such a difference. I highly recommend Dr. Ve Aesthetic to anyone looking for quality care."
                                </p>
                            </div>
                            <div class="feedback-author" style="border-top: 1px solid #e1e8ed; padding-top: 20px;">
                                <div class="author-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                    <i class="fas fa-user" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px;">Jennifer K.</h5>
                                <p style="color: #999; font-size: 0.9rem; margin-bottom: 0;">Anti-Aging Client</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="text-center mt-5">
                @auth
                    <a href="{{ route('feedback.create') }}" class="theme-btn" style="background: white; color: #fbaaa9; border: none; padding: 15px 40px; border-radius: 30px; font-size: 1.1rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; position: relative; overflow: hidden;">
                        <span style="position: relative; z-index: 2;">Share Your Experience</span>
                        <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(251, 170, 169, 0.1), transparent); transition: left 0.5s;"></div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="theme-btn" style="background: white; color: #fbaaa9; border: none; padding: 15px 40px; border-radius: 30px; font-size: 1.1rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; position: relative; overflow: hidden;">
                        <span style="position: relative; z-index: 2;">Share Your Experience</span>
                        <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(251, 170, 169, 0.1), transparent); transition: left 0.5s;"></div>
                    </a>
                @endauth
            </div>
        </div>
    </section>
    <!--====================================================================
                                   End Customer Feedback Section
                               =====================================================================-->

@endsection