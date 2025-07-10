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
                                <h2 class="display-4 fw-bold mb-4">Welcome to Our Shop</h2>
                                <p class="lead">Discover amazing products at great prices</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-2 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">Summer Sale</h2>
                                <p class="lead">Up to 50% off on selected items</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100">
                        <div class="carousel-img-3 d-flex align-items-center justify-content-center">
                            <div class="text-center text-white p-4">
                                <h2 class="display-4 fw-bold mb-4">New Arrivals</h2>
                                <p class="lead">Check out our latest collection</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-3">Welcome Back!</h2>
                    <p class="text-muted">Please sign in to your account</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                id="username" name="username" value="{{ old('username') }}" 
                                placeholder="Enter your username" required autocomplete="username" autofocus>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Enter your password" 
                                required autocomplete="current-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                        Sign In
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
