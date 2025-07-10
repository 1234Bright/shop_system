@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side - Carousel -->
        <div class="col-md-6 d-none d-md-block p-0">
            <div id="shopCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#shopCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#shopCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#shopCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner h-100">
                    <div class="carousel-item active h-100">
                        <div class="carousel-img-1 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Create an Account</h2>
                                <p class="lead">Join our shop and start shopping today</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-2 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Exclusive Offers</h2>
                                <p class="lead">Get access to member-only deals</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-3 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Fast Checkout</h2>
                                <p class="lead">Save your details for a seamless shopping experience</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-3">Create an Account</h2>
                    <p class="text-muted">Sign up to get started with our shop</p>
                </div>
                
                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" 
                                placeholder="Enter your full name" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" 
                                placeholder="Enter your email" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Create a password" 
                                required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Password must be at least 8 characters</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg" 
                                id="password-confirm" name="password_confirmation" 
                                placeholder="Confirm your password" required autocomplete="new-password">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                        Register Account
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
