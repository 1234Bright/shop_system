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
                                <h2 class="display-4 fw-bold mb-4">Recover Your Account</h2>
                                <p class="lead">We'll help you get back in</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-2 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Secure Reset Process</h2>
                                <p class="lead">We take your security seriously</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-3 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Get Back Shopping</h2>
                                <p class="lead">Quickly reset your password and continue shopping</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Password Reset Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-3">Reset Your Password</h2>
                    <p class="text-muted">Enter your email address and we will send you a link to reset your password</p>
                </div>
                
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" 
                                placeholder="Enter your email" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                        Send Reset Link
                    </button>
                    
                    <div class="text-center">
                        <p class="mb-0"><a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
