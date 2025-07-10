@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-title fw-bold">Dashboard</h1>
            <p class="text-muted">Welcome back! Here's an overview of your shop performance</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-subtitle text-muted">Total Sales</h6>
                        <div class="stat-icon bg-primary-light text-primary">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold mb-0">$24,780</h3>
                    <p class="stat-change positive mt-2 mb-0">
                        <i class="fas fa-arrow-up me-1"></i> 12.8%
                        <span class="text-muted ms-1">vs last month</span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-subtitle text-muted">New Orders</h6>
                        <div class="stat-icon bg-success-light text-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold mb-0">347</h3>
                    <p class="stat-change positive mt-2 mb-0">
                        <i class="fas fa-arrow-up me-1"></i> 8.3%
                        <span class="text-muted ms-1">vs last month</span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-subtitle text-muted">New Customers</h6>
                        <div class="stat-icon bg-info-light text-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold mb-0">124</h3>
                    <p class="stat-change positive mt-2 mb-0">
                        <i class="fas fa-arrow-up me-1"></i> 5.7%
                        <span class="text-muted ms-1">vs last month</span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-subtitle text-muted">Low Stock Items</h6>
                        <div class="stat-icon bg-warning-light text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold mb-0">12</h3>
                    <p class="stat-change negative mt-2 mb-0">
                        <i class="fas fa-arrow-up me-1"></i> 2.4%
                        <span class="text-muted ms-1">vs last month</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    

    
    <!-- Recent Activities and Low Stock -->
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-7652</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=7F9CF5&color=fff" class="rounded-circle me-2" width="32" height="32">
                                            <div>John Doe</div>
                                        </div>
                                    </td>
                                    <td>Jul 9, 2025</td>
                                    <td>$459.65</td>
                                    <td><span class="badge bg-success-light text-success">Completed</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Invoice</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-7651</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=7F9CF5&color=fff" class="rounded-circle me-2" width="32" height="32">
                                            <div>Sarah Johnson</div>
                                        </div>
                                    </td>
                                    <td>Jul 8, 2025</td>
                                    <td>$189.25</td>
                                    <td><span class="badge bg-warning-light text-warning">Processing</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Invoice</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-7650</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Michael+Brown&background=7F9CF5&color=fff" class="rounded-circle me-2" width="32" height="32">
                                            <div>Michael Brown</div>
                                        </div>
                                    </td>
                                    <td>Jul 7, 2025</td>
                                    <td>$298.40</td>
                                    <td><span class="badge bg-success-light text-success">Completed</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Invoice</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-7649</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Emma+Wilson&background=7F9CF5&color=fff" class="rounded-circle me-2" width="32" height="32">
                                            <div>Emma Wilson</div>
                                        </div>
                                    </td>
                                    <td>Jul 7, 2025</td>
                                    <td>$542.75</td>
                                    <td><span class="badge bg-danger-light text-danger">Cancelled</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Invoice</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Low Stock Alert</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="product-img me-3 bg-light rounded">
                                        <i class="fas fa-box p-3 text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Wireless Earbuds</h6>
                                        <small class="text-muted">#PRD-345</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-danger">3 left</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="product-img me-3 bg-light rounded">
                                        <i class="fas fa-box p-3 text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Smart Watch</h6>
                                        <small class="text-muted">#PRD-128</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-danger">5 left</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="product-img me-3 bg-light rounded">
                                        <i class="fas fa-box p-3 text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">USB-C Hub</h6>
                                        <small class="text-muted">#PRD-542</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-warning">8 left</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="product-img me-3 bg-light rounded">
                                        <i class="fas fa-box p-3 text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Wireless Keyboard</h6>
                                        <small class="text-muted">#PRD-298</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-warning">10 left</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dashboard initialization code
        console.log('Dashboard initialized');
        
        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert-dismissible');
        if (alerts.length > 0) {
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                }, 5000);
            });
        }
    });
</script>
@endsection
