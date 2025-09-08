@extends('layouts.app')

@section('title', 'Edit Profile - ' . config('app.name'))

@section('content')
<section class="contact-section pt-150 rpt-100 pb-150 rpb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-form-wrap">
                    <div class="section-title text-center mb-50">
                        <h2>Edit Profile</h2>
                        <p>Update your personal information and account settings</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Please correct the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Profile Picture Section -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <div class="mb-3">
                                        @if (auth()->user()->avatar_url)
                                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                                class="rounded-circle border mx-auto d-block" width="150" height="150" id="profilePreview">
                                        @else
                                            <div class="avatar-placeholder-large rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold mx-auto" id="profilePreview">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="avatar" class="btn btn-primary">
                                            <i class="fa fa-camera mr-1"></i> Change Photo
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                    </div>
                                    <small class="text-muted">JPG, PNG or GIF (Max. 2MB)</small>
                                </div>
                            </div>

                            <!-- Profile Information Section -->
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Section -->
                        <hr class="my-4">
                        <h4 class="mb-3"><i class="fa fa-lock mr-2"></i> Change Password (Optional)</h4>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <a href="{{ url('/users/dashboard') }}" class="btn btn-secondary mr-3">
                                <i class="fa fa-arrow-left mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .avatar-placeholder-large {
        width: 150px;
        height: 150px;
        font-size: 3rem;
    }
    
    .contact-form-wrap {
        background: #fff;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .section-title h2 {
        color: #333;
        font-weight: 700;
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Profile picture preview
        $('#avatar').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img element
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = '{{ auth()->user()->name }}';
                        img.className = 'rounded-circle border mx-auto d-block';
                        img.width = 150;
                        img.height = 150;
                        img.id = 'profilePreview';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush