@extends('layouts.app')

@section('title', 'About Us | Aesthetic - Dermatology and Skin Care')

@section('about-active', 'current')

@section('content')

    <style>
        /* Custom styling for Mission and Vision headings */
        .vission-mission h3 {
            font-family: 'Georgia', 'Times New Roman', serif !important;
            font-weight: 700 !important;
            letter-spacing: 1px !important;
        }
        
        /* Make Vision heading white */
        .vission.vission-mission h3 {
            color: white !important;
        }
        
        /* Make Mission heading a darker color for better contrast */
        .mission.vission-mission h3 {
            color: #2c3e50 !important;
        }
        
        /* Add borders to about images */
        .about-images img {
            border: 3px solid #fbaaa9 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease !important;
        }
        
        .about-images img:hover {
            border-color: #e74c3c !important;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px) !important;
        }
        
    </style>

        <!--====================================================================
           Start Banner Section
       =====================================================================-->
        <section class="banner-section" @if(setting('general.about_banner_image')) style="background-image: url('{{ Storage::url(setting('general.about_banner_image')) }}'); position: relative;" @else style="position: relative;" @endif>
            <!-- Black transparent overlay -->
            <div style="position: absolute; 
                 top: 0; 
                 left: 0; 
                 right: 0; 
                 bottom: 0; 
                 background: rgba(0, 0, 0, 0.5); 
                 z-index: 1;"></div>
            <div class="container" style="position: relative; z-index: 2;">
                <div class="banner-inner" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div class="banner-content">
                        <h2 class="page-title" style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                            <span style="color: #fbaaa9;">About</span> <span style="color: white;">Us</span>
                        </h2>
                        <h4 style="font-size: 1.5rem; font-weight: 400; margin-bottom: 1.5rem; color: white;">
                            The Art of Natural Enhancement
                        </h4>
                    </div>
                    <nav aria-label="breadcrumb" style="margin-top: 2rem;">
                        <ol class="breadcrumb" style="justify-content: center;">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: white; text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #fbaaa9;">About Us</li>
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
           Start About Section
       =====================================================================-->
        <section class="about-section my-150 rmt-125 rmb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="about-images rmb-50">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====================================================================
           End About Section
       =====================================================================-->



        <!--====================================================================
           Start Vission Mission section
        =====================================================================-->
        <section class="vission-misson-section bg-four mb-150 rmb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mission vission-mission">
                            <h3>{{ setting('general.mission_title', 'Our Mission.') }}</h3>
                            <h6>{{ setting('general.mission_subtitle', 'Saunas are used all over the world to improve health enjoy relax. During the clients stay in sauna, body is sweating and from harmful substances and toxins.') }}</h6>
                            <p>{{ setting('general.mission_description', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                            <!--<a href="{{ url('/services') }}" class="theme-btn style-two mt-15">Our Services</a>-->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="vission vission-mission bg-three text-white">
                            <h3>{{ setting('general.vision_title', 'Our Vision.') }}</h3>
                            <p>{{ setting('general.vision_description_1', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                            <p>{{ setting('general.vision_description_2', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                            <!--<a href="{{ url('/about') }}" class="theme-btn style-four mt-15">Our Experts</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====================================================================
           End Vission Mission Section
       =====================================================================-->



        <!--====================================================================
                                Start Team section
        =====================================================================-->
        <section class="team-section bg-two pt-145 pb-150 rpt-95 rpb-70">
            <div class="container">
                <div class="section-title mb-90">
                    <h2>{{ setting('team.section_title', 'Our Expert And Dedicated Team.') }}</h2>
                    @if(setting('team.section_description'))
                        <p class="mt-3">{{ setting('team.section_description') }}</p>
                    @endif
                </div>

                <div class="team-carousel owl-carousel style-two">
                    @foreach(setting('team.members', [
                        ['name' => 'Dr. Sarah Johnson', 'position' => 'Chief Dermatologist', 'image' => 'assets/images/team/team1.png'],
                        ['name' => 'Dr. Michael Chen', 'position' => 'Aesthetic Specialist', 'image' => 'assets/images/team/team2.png'],
                        ['name' => 'Emily Rodriguez', 'position' => 'Senior Aesthetician', 'image' => 'assets/images/team/team3.png']
                    ]) as $member)
                        <div class="team-item">
                            @if(isset($member['image']) && $member['image'])
                                @if(str_starts_with($member['image'], 'team-members/'))
                                    <img src="{{ Storage::url($member['image']) }}" alt="{{ $member['name'] ?? 'Team Member' }}">
                                @else
                                    <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] ?? 'Team Member' }}">
                                @endif
                            @else
                                <img src="{{ asset('assets/images/team/default-team.png') }}" alt="{{ $member['name'] ?? 'Team Member' }}">
                            @endif
                            <h5>{{ $member['name'] ?? 'Team Member' }}</h5>
                            <p>{{ $member['position'] ?? 'Team Member' }}</p>
                            @if(isset($member['bio']) && $member['bio'])
                                <div class="team-bio mt-2">
                                    <small>{{ Str::limit($member['bio'], 500) }}</small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <!--====================================================================
                                End Team section
        =====================================================================-->


@endsection
