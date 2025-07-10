@extends('layouts.app')

@section('title', 'Sales Dashboard')

@section('content')
<div class="container py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">Welcome, {{ Auth::user()->name }}!</h2>
                            <p class="text-muted mb-0">{{ ucfirst(Auth::user()->user_type) }} Dashboard</p>
                        </div>
                        <div>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Dashboard Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today's Date</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ date('F j, Y') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Your Profile</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ Auth::user()->username }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Phone Number</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ Auth::user()->phone_number }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-phone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Profile</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 text-center mb-4 mb-lg-0">
                            @if(Auth::user()->picture)
                                <img src="{{ asset('storage/' . Auth::user()->picture) }}" class="img-profile rounded-circle img-thumbnail" style="width: 150px; height: 150px;" alt="Profile Picture">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mx-auto" style="width: 150px; height: 150px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-9">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Full Name</th>
                                            <td>{{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Username</th>
                                            <td>{{ Auth::user()->username }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ Auth::user()->email ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{ Auth::user()->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>User Type</th>
                                            <td>
                                                <span class="badge {{ Auth::user()->user_type == 'admin' ? 'bg-primary' : 'bg-info' }}">
                                                    {{ ucfirst(Auth::user()->user_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any dashboard-specific JavaScript can go here
</script>
@endsection
