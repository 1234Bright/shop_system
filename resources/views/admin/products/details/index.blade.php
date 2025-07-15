@extends('layouts.admin')

@section('title', 'Product Details - ' . $product->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Product Details: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('admin.products.details.create', $product->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Add Variant
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row mb-4">
        <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 150px;">
                                <i class="fas fa-box fa-3x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-3">{{ $product->name }}</h5>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Product Code:</span>
                        <span class="badge bg-light text-dark">{{ $product->product_code }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Category:</span>
                        <span>{{ $product->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Brand:</span>
                        <span>{{ $product->brand->name ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Base Price:</span>
                        <span>${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Status:</span>
                        @if($product->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Total Stock:</span>
                        <span class="badge bg-info text-white">{{ $totalQuantity }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold text-muted">Variants:</span>
                        <span class="badge bg-secondary">{{ $product->details->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-0">Product Variants</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="variantSearch" class="form-control" placeholder="Search variants..." aria-label="Search variants">
                                <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Variant Code</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="variantTableBody">
                                @forelse($product->details as $detail)
                                <tr class="variant-row">
                                    <td><span class="badge bg-light text-dark">{{ $detail->product_code }}</span></td>
                                    <td>{{ $detail->size->name ?? 'N/A' }}</td>
                                    <td>{{ $detail->color ?? 'N/A' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>${{ number_format($detail->price, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.details.edit', [$product->id, $detail->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteVariantModal{{ $detail->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Delete Variant Modal -->
                                <div class="modal fade" id="deleteVariantModal{{ $detail->id }}" tabindex="-1" aria-labelledby="deleteVariantModalLabel{{ $detail->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteVariantModalLabel{{ $detail->id }}">Delete Product Variant</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this product variant ({{ $detail->product_code }})? This action cannot be undone.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.products.details.destroy', [$product->id, $detail->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No product variants found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('variantSearch');
        const variantRows = document.querySelectorAll('.variant-row');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            variantRows.forEach(row => {
                const code = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const size = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const color = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (code.includes(searchTerm) || 
                    size.includes(searchTerm) || 
                    color.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
