@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Categories</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Categories</li>
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
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search categories...">
                        <span class="input-group-text bg-transparent border-start-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-1"></i> Create Category
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
                                    <th>Category Name</th>
                                    <th width="100" class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                <tr>
                                    <td class="ps-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input category-check" value="{{ $category->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $category->name }}</div>
                                        <div class="text-muted small">{{ $category->slug }}</div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary btn-sm edit-category" 
                                                data-id="{{ $category->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-folder-open fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No categories found</h5>
                                            <p class="text-muted small mb-0">Get started by adding your first category</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light border-top px-3 py-2 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing <span id="showing-entries">{{ $categories->count() }}</span> entries
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="255" placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" maxlength="255" placeholder="enter-slug-or-leave-blank">
                        <div class="form-text">Will be auto-generated from name if left blank</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="edit_slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="edit_slug" name="slug" maxlength="255">
                        <div class="form-text">Will be auto-generated from name if left blank</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
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
        
        // Select all checkbox functionality
        const selectAll = document.getElementById('selectAll');
        const categoryChecks = document.querySelectorAll('.category-check');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                categoryChecks.forEach(function(check) {
                    check.checked = selectAll.checked;
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
                    const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    
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
        const editButtons = document.querySelectorAll('.edit-category');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-id');
                const formAction = `{{ route('admin.categories.index') }}/${categoryId}`;
                
                // Set form action
                document.getElementById('editCategoryForm').action = formAction;
                
                // Fetch category data
                fetch(`{{ route('admin.categories.index') }}/${categoryId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_slug').value = data.slug;
                    });
            });
        });
        
        // Auto-generate slug on create
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('keyup', function() {
                if (!slugInput.value) {
                    slugInput.value = nameInput.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            });
        }
        
        // Auto-generate slug on edit
        const editNameInput = document.getElementById('edit_name');
        const editSlugInput = document.getElementById('edit_slug');
        
        if (editNameInput && editSlugInput) {
            editNameInput.addEventListener('keyup', function() {
                if (!editSlugInput.value) {
                    editSlugInput.value = editNameInput.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            });
        }
        
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
