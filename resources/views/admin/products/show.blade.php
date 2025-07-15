@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Product Details</h1>
        <div>
            <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-info me-2">
                <i class="fas fa-list-alt me-2"></i>Manage Variants
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Product Name:</div>
                        <div class="col-md-8">{{ $product->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Product Code:</div>
                        <div class="col-md-8"><span class="badge bg-light text-dark">{{ $product->product_code }}</span></div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Category:</div>
                        <div class="col-md-8">{{ $product->category->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Brand:</div>
                        <div class="col-md-8">{{ $product->brand->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Price:</div>
                        <div class="col-md-8">${{ number_format($product->price, 2) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Cost:</div>
                        <div class="col-md-8">{{ $product->cost ? '$'.number_format($product->cost, 2) : 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Status:</div>
                        <div class="col-md-8">
                            @if($product->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-muted">Created At:</div>
                        <div class="col-md-8">{{ $product->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 fw-bold text-muted">Updated At:</div>
                        <div class="col-md-8">{{ $product->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-muted mb-0">No description provided.</p>
                    @endif
                </div>
            </div>
            
            <!-- Product Variants Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Product Variants</h5>
                    <a href="{{ route('admin.products.details.create', $product->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Variant
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($product->details->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Variant Code</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->details as $detail)
                                    <tr>
                                        <td><span class="badge bg-light text-dark">{{ $detail->product_code }}</span></td>
                                        <td>{{ $detail->size->name ?? 'N/A' }}</td>
                                        <td>{{ $detail->color ?? 'N/A' }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>${{ number_format($detail->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 py-2 text-end">
                            <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-sm btn-link">
                                Manage all variants <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <p class="text-muted mb-3">No variants have been added for this product.</p>
                            <a href="{{ route('admin.products.details.create', $product->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Add First Variant
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Product Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded py-5">
                            <div class="text-center">
                                <i class="fas fa-box fa-4x text-secondary mb-3"></i>
                                <p class="text-muted mb-0">No image available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
