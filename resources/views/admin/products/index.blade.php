@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Product
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Manage Products</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="productSearch" class="form-control" placeholder="Search products..." aria-label="Search products">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 align-items-center mt-2">
                <div class="col-md-4">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="brandFilter" class="form-select">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button id="resetFilters" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70px;">Image</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @forelse($products as $product)
                        <tr class="product-row" data-product-id="{{ $product->id }}" style="cursor: pointer;">
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $product->product_code }}</span></td>
                            <td>{{ $product->name }}</td>
                            <td data-category-id="{{ $product->category_id }}">{{ $product->category->name ?? 'N/A' }}</td>
                            <td data-brand-id="{{ $product->brand_id }}">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ number_format($product->quantity) }}</td>
                            <td>
                                @if($product->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-sm btn-outline-secondary" title="Manage Variants">
                                        <i class="fas fa-list-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->id }}" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Variants Row -->
                        <tr class="variant-details-row" id="variants-{{ $product->id }}" style="display: none;">
                            <td colspan="9" class="p-0 border-0">
                                <div class="variant-details bg-light p-3 rounded-bottom shadow-sm border-top-0" style="margin-top: -10px;">
                                    <h6 class="fw-bold mb-2"><i class="fas fa-tags me-2"></i>Product Variants</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 bg-white">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Size</th>
                                                    <th>Color</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody class="variants-container" data-loading="false">
                                                <tr class="variants-loader text-center">
                                                    <td colspan="5"><i class="fas fa-spinner fa-spin me-2"></i> Loading variants...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-end mt-2">
                                        <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-list-alt me-1"></i> Manage Variants
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Delete Product Modal -->
                        <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteProductModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteProductModalLabel{{ $product->id }}">Delete Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete product <strong>{{ $product->name }}</strong>? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
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
                            <td colspan="8" class="text-center py-4">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('productSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const brandFilter = document.getElementById('brandFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const productRows = document.querySelectorAll('.product-row');
        
        // Function to filter the table
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategoryId = categoryFilter.value;
            const selectedBrandId = brandFilter.value;
            
            productRows.forEach(row => {
                const code = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const categoryCell = row.querySelector('td:nth-child(4)');
                const brandCell = row.querySelector('td:nth-child(5)');
                
                // Get data attributes for more reliable filtering
                const categoryId = categoryCell.getAttribute('data-category-id') || '';
                const brandId = brandCell.getAttribute('data-brand-id') || '';
                
                const categoryText = categoryCell.textContent.toLowerCase();
                const brandText = brandCell.textContent.toLowerCase();
                
                // Check if meets search criteria
                const matchesSearch = !searchTerm || 
                    code.includes(searchTerm) || 
                    name.includes(searchTerm) || 
                    categoryText.includes(searchTerm) ||
                    brandText.includes(searchTerm);
                    
                // Check if meets category filter criteria
                const matchesCategory = !selectedCategoryId || categoryId === selectedCategoryId;
                
                // Check if meets brand filter criteria
                const matchesBrand = !selectedBrandId || brandId === selectedBrandId;
                
                // Show the row only if it matches all criteria
                if (matchesSearch && matchesCategory && matchesBrand) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    
                    // Also hide any expanded variant rows
                    const productId = row.getAttribute('data-product-id');
                    const variantRow = document.getElementById(`variants-${productId}`);
                    if (variantRow) {
                        variantRow.style.display = 'none';
                    }
                }
            });
        }
        
        // Event listeners
        searchInput.addEventListener('input', filterTable);
        categoryFilter.addEventListener('change', filterTable);
        brandFilter.addEventListener('change', filterTable);
        
        // Reset filters
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            categoryFilter.value = '';
            brandFilter.value = '';
            
            // Show all rows
            productRows.forEach(row => {
                row.style.display = '';
            });
        });
        
        // Expandable rows functionality
        productRows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't expand if clicking on action buttons
                if (e.target.closest('.btn-group')) {
                    return;
                }
                
                const productId = this.getAttribute('data-product-id');
                const variantRow = document.getElementById(`variants-${productId}`);
                
                if (variantRow) {
                    // Toggle visibility
                    const isExpanded = variantRow.style.display !== 'none';
                    
                    // Close all other expanded rows first
                    document.querySelectorAll('.variant-details-row').forEach(r => {
                        r.style.display = 'none';
                    });
                    
                    // Toggle the clicked row
                    if (isExpanded) {
                        variantRow.style.display = 'none';
                        row.classList.remove('active-row');
                    } else {
                        variantRow.style.display = 'table-row';
                        row.classList.add('active-row');
                        loadVariants(productId);
                    }
                }
            });
        });
        
        // Function to load variants via AJAX
        function loadVariants(productId) {
            const variantsContainer = document.querySelector(`#variants-${productId} .variants-container`);
            
            // Check if already loaded or loading
            if (variantsContainer.getAttribute('data-loaded') === 'true' || 
                variantsContainer.getAttribute('data-loading') === 'true') {
                return;
            }
            
            // Set loading state
            variantsContainer.setAttribute('data-loading', 'true');
            
            // Make AJAX request to get variants
            fetch(`/admin/products/${productId}/details/json`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear loading indicator
                    variantsContainer.innerHTML = '';
                    
                    if (data.details.length === 0) {
                        variantsContainer.innerHTML = '<tr><td colspan="5" class="text-center">No variants found for this product</td></tr>';
                    } else {
                        // Add variant rows
                        data.details.forEach(detail => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><span class="badge bg-light text-dark">${detail.product_code}</span></td>
                                <td>${detail.size ? detail.size.name : 'N/A'}</td>
                                <td>${detail.color || 'N/A'}</td>
                                <td>${detail.quantity}</td>
                                <td>$${parseFloat(detail.price).toFixed(2)}</td>
                            `;
                            variantsContainer.appendChild(row);
                        });
                    }
                    
                    // Mark as loaded
                    variantsContainer.setAttribute('data-loading', 'false');
                    variantsContainer.setAttribute('data-loaded', 'true');
                })
                .catch(error => {
                    console.error('Error loading variants:', error);
                    variantsContainer.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading variants</td></tr>';
                    variantsContainer.setAttribute('data-loading', 'false');
                });
        }
    });
</script>

<style>
    /* Styling for expandable rows */
    .product-row {
        transition: background-color 0.2s ease;
    }
    
    .product-row:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .product-row.active-row {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .variant-details {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
