@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Brands</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Brands</li>
                    </ol>
                </nav>
            </div>
            
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="input-group input-group-sm" style="max-width: 300px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search brands...">
                        <span class="input-group-text bg-transparent border-start-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                        <i class="fas fa-plus me-1"></i> Create Brand
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase small">
                                <tr>
                                    <th width="40" class="ps-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </div>
                                    </th>
                                    <th width="80">Logo</th>
                                    <th>Brand Name</th>
                                    <th width="100" class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($brands as $brand)
                                <tr>
                                    <td class="ps-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input brand-check" value="{{ $brand->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-thumbnail" width="50">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $brand->name }}</div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary btn-sm edit-brand" 
                                                data-id="{{ $brand->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBrandModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="mb-0 text-muted">No brands found</p>
                                            <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                                Create your first brand
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light border-top px-3 py-2 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing <span id="showing-entries">{{ count($brands) }}</span> of {{ count($brands) }} entries
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="255" placeholder="Enter brand name">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        <div class="form-text">Upload a brand logo (optional, max 2MB)</div>
                        <div class="mt-2" id="logo-preview-container" style="display: none;">
                            <img id="logo-preview" src="#" alt="Logo Preview" class="img-thumbnail" style="max-height: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-1" id="remove-logo">Remove</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBrandForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="edit_logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                        <div class="form-text">Upload a new brand logo (optional, max 2MB)</div>
                        <div class="mt-2" id="edit-logo-preview">
                            <img id="current-logo" src="" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Handle select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.brand-check');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
        
        // Handle search functionality
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                let visibleCount = 0;
                
                rows.forEach(function(row) {
                    const name = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                    
                    if (name.includes(searchValue)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update showing entries count
                const showingEntries = document.getElementById('showing-entries');
                if (showingEntries) {
                    showingEntries.textContent = visibleCount;
                }
            });
        }
        
        // Handle edit button click
        const editButtons = document.querySelectorAll('.edit-brand');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const brandId = this.getAttribute('data-id');
                const formAction = `{{ route('admin.brands.index') }}/${brandId}`;
                
                // Set form action
                document.getElementById('editBrandForm').action = formAction;
                
                // Fetch brand data
                fetch(`{{ route('admin.brands.index') }}/${brandId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_name').value = data.name;
                        
                        // Handle logo preview
                        const logoPreview = document.getElementById('current-logo');
                        if (data.logo) {
                            logoPreview.src = '{{ asset('storage') }}/' + data.logo;
                            document.getElementById('edit-logo-preview').style.display = '';
                        } else {
                            document.getElementById('edit-logo-preview').style.display = 'none';
                        }
                    });
            });
        });
        
        // Logo preview for add modal
        const logoInput = document.getElementById('logo');
        const logoPreviewContainer = document.getElementById('logo-preview-container');
        const logoPreview = document.getElementById('logo-preview');
        const removeLogoButton = document.getElementById('remove-logo');
        
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        logoPreview.src = e.target.result;
                        logoPreviewContainer.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
        
        if (removeLogoButton) {
            removeLogoButton.addEventListener('click', function() {
                logoInput.value = '';
                logoPreviewContainer.style.display = 'none';
            });
        }
        
        // Logo preview for edit modal
        const editLogoInput = document.getElementById('edit_logo');
        
        if (editLogoInput) {
            editLogoInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        document.getElementById('current-logo').src = e.target.result;
                        document.getElementById('edit-logo-preview').style.display = 'block';
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
        
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this brand? This action cannot be undone.')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
