@extends('layouts.app')

@section('title', 'About Us | Aesthetic - Dermatology and Skin Care')

@section('about-active', 'current')

@section('content')


        <!--====================================================================
           Start Banner Section
       =====================================================================-->
        <section class="banner-section" @if(setting('general.about_banner_image')) style="background-image: url('{{ Storage::url(setting('general.about_banner_image')) }}');" @endif>
            <div class="container">
                <div class="banner-inner">
                    <div class="banner-content">
                        <h2 class="page-title">{{ setting('general.about_banner_title', 'About Us.') }}</h2>
                        <p>{{ setting('general.about_banner_description', 'Saunas are used all over the world to improve health, to enjoy and relax.') }}</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
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
                            <a href="{{ url('/services') }}" class="theme-btn style-two mt-15">Our Services</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="vission vission-mission bg-three text-white">
                            <h3>{{ setting('general.vision_title', 'Our Vision.') }}</h3>
                            <p>{{ setting('general.vision_description_1', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                            <p>{{ setting('general.vision_description_2', 'It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.') }}</p>
                            <a href="{{ url('/about') }}" class="theme-btn style-four mt-15">Our Experts</a>
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
                                    <small>{{ Str::limit($member['bio'], 100) }}</small>
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
