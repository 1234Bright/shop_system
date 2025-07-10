@extends('layouts.admin')

@section('title', 'Supplier Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Suppliers</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="fas fa-plus me-2"></i>Add Supplier
        </button>
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
                <div class="col-md-8">
                    <h5 class="card-title mb-0">Manage Suppliers</h5>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="supplierSearch" class="form-control" placeholder="Search suppliers..." aria-label="Search suppliers">
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
                            <th style="width: 60px;">ID</th>
                            <th style="width: 70px;">Photo</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone 1</th>
                            <th>Phone 2</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="supplierTableBody">
                        @forelse($suppliers as $supplier)
                        <tr class="supplier-row">
                            <td>{{ $supplier->id }}</td>
                            <td>
                                @if($supplier->picture)
                                    <img src="{{ asset('storage/' . $supplier->picture) }}" alt="{{ $supplier->name }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->address ?? 'N/A' }}</td>
                            <td>{{ $supplier->phone1 ?? 'N/A' }}</td>
                            <td>{{ $supplier->phone2 ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-supplier-btn" data-id="{{ $supplier->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal{{ $supplier->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Delete Supplier Modal -->
                        <div class="modal fade" id="deleteSupplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="deleteSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteSupplierModalLabel{{ $supplier->id }}">Delete Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete supplier <strong>{{ $supplier->name }}</strong>? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST">
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
                            <td colspan="7" class="text-center py-4">No suppliers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone1" class="form-label">Phone Number 1</label>
                        <input type="text" class="form-control" id="phone1" name="phone1">
                    </div>
                    <div class="mb-3">
                        <label for="phone2" class="form-label">Phone Number 2</label>
                        <input type="text" class="form-control" id="phone2" name="phone2">
                    </div>
                    <div class="mb-3">
                        <label for="picture" class="form-label">Supplier Picture</label>
                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                        <div id="picturePreviewContainer" class="mt-2" style="display: none;">
                            <img id="picturePreview" class="img-thumbnail customer-image-preview">
                            <button type="button" id="removePicture" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSupplierForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone1" class="form-label">Phone Number 1</label>
                        <input type="text" class="form-control" id="edit_phone1" name="phone1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone2" class="form-label">Phone Number 2</label>
                        <input type="text" class="form-control" id="edit_phone2" name="phone2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier Picture</label>
                        <!-- Current picture display -->
                        <div id="currentPictureContainer" class="mb-2" style="display: none;">
                            <p class="text-muted small">Current Picture:</p>
                            <img id="currentPicture" class="img-thumbnail customer-image-preview">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="remove_picture" name="remove_picture" value="1">
                                <label class="form-check-label" for="remove_picture">
                                    Remove current picture
                                </label>
                            </div>
                        </div>
                        <!-- New picture upload -->
                        <input type="file" class="form-control" id="edit_picture" name="picture" accept="image/*">
                        <div id="editPicturePreviewContainer" class="mt-2" style="display: none;">
                            <p class="text-muted small">New Picture:</p>
                            <img id="editPicturePreview" class="img-thumbnail customer-image-preview">
                            <button type="button" id="editRemovePicture" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('supplierSearch');
        const supplierRows = document.querySelectorAll('.supplier-row');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            supplierRows.forEach(row => {
                const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const address = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const phone1 = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                const phone2 = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || 
                    address.includes(searchTerm) || 
                    phone1.includes(searchTerm) || 
                    phone2.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Picture preview for add modal
        const pictureInput = document.getElementById('picture');
        const picturePreview = document.getElementById('picturePreview');
        const picturePreviewContainer = document.getElementById('picturePreviewContainer');
        const removePicture = document.getElementById('removePicture');
        
        pictureInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    picturePreview.src = e.target.result;
                    picturePreviewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        removePicture.addEventListener('click', function() {
            pictureInput.value = '';
            picturePreviewContainer.style.display = 'none';
        });
        
        // Edit supplier
        const editButtons = document.querySelectorAll('.edit-supplier-btn');
        const editSupplierForm = document.getElementById('editSupplierForm');
        const editSupplierModal = document.getElementById('editSupplierModal');
        
        // Picture preview for edit modal
        const editPictureInput = document.getElementById('edit_picture');
        const editPicturePreview = document.getElementById('editPicturePreview');
        const editPicturePreviewContainer = document.getElementById('editPicturePreviewContainer');
        const editRemovePicture = document.getElementById('editRemovePicture');
        const currentPicture = document.getElementById('currentPicture');
        const currentPictureContainer = document.getElementById('currentPictureContainer');
        
        editPictureInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    editPicturePreview.src = e.target.result;
                    editPicturePreviewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        editRemovePicture.addEventListener('click', function() {
            editPictureInput.value = '';
            editPicturePreviewContainer.style.display = 'none';
        });
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const supplierId = this.dataset.id;
                
                // Reset the form and previews
                editSupplierForm.reset();
                editPicturePreviewContainer.style.display = 'none';
                currentPictureContainer.style.display = 'none';
                
                // Set the form action
                editSupplierForm.action = `/admin/suppliers/${supplierId}`;
                
                // Fetch supplier data
                fetch(`/admin/suppliers/${supplierId}/edit`)
                    .then(response => response.json())
                    .then(supplier => {
                        document.getElementById('edit_name').value = supplier.name;
                        document.getElementById('edit_address').value = supplier.address || '';
                        document.getElementById('edit_phone1').value = supplier.phone1 || '';
                        document.getElementById('edit_phone2').value = supplier.phone2 || '';
                        
                        // Show current picture if exists
                        if (supplier.picture) {
                            currentPicture.src = `/storage/${supplier.picture}`;
                            currentPictureContainer.style.display = 'block';
                        }
                        
                        // Show the edit modal
                        const modal = new bootstrap.Modal(editSupplierModal);
                        modal.show();
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection
