@extends('layouts.admin')

@section('title', 'Customer Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Customers</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-plus me-2"></i>Add Customer
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
                    <h5 class="card-title mb-0">Manage Customers</h5>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="customerSearch" class="form-control" placeholder="Search customers..." aria-label="Search customers">
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
                            <th style="width: 70px;">Photo</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone 1</th>
                            <th>Phone 2</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        @forelse($customers as $customer)
                        <tr class="customer-row">
                            <td>
                                @if($customer->picture)
                                    <img src="{{ asset('storage/' . $customer->picture) }}" alt="{{ $customer->name }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address ?? 'N/A' }}</td>
                            <td>{{ $customer->phone1 ?? 'N/A' }}</td>
                            <td>{{ $customer->phone2 ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-customer-btn" data-id="{{ $customer->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal{{ $customer->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Delete Customer Modal -->
                        <div class="modal fade" id="deleteCustomerModal{{ $customer->id }}" tabindex="-1" aria-labelledby="deleteCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteCustomerModalLabel{{ $customer->id }}">Delete Customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete customer <strong>{{ $customer->name }}</strong>? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
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
                            <td colspan="6" class="text-center py-4">No customers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label for="picture" class="form-label">Customer Picture</label>
                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                        <div id="picturePreviewContainer" class="mt-2" style="display: none;">
                            <img id="picturePreview" class="img-thumbnail" style="max-height: 150px;">
                            <button type="button" id="removePicture" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Customer Picture</label>
                        <!-- Current picture display -->
                        <div id="currentPictureContainer" class="mb-2" style="display: none;">
                            <p class="text-muted small">Current Picture:</p>
                            <img id="currentPicture" class="img-thumbnail" style="max-height: 150px;">
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
                            <img id="editPicturePreview" class="img-thumbnail" style="max-height: 150px;">
                            <button type="button" id="editRemovePicture" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
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
        const searchInput = document.getElementById('customerSearch');
        const customerRows = document.querySelectorAll('.customer-row');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            customerRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const address = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const phone1 = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const phone2 = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                
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
        const removePictureBtn = document.getElementById('removePicture');
        
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
        
        removePictureBtn.addEventListener('click', function() {
            pictureInput.value = '';
            picturePreviewContainer.style.display = 'none';
        });
        
        // Edit customer functionality
        const editButtons = document.querySelectorAll('.edit-customer-btn');
        const editCustomerModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
        const editCustomerForm = document.getElementById('editCustomerForm');
        const editName = document.getElementById('edit_name');
        const editAddress = document.getElementById('edit_address');
        const editPhone1 = document.getElementById('edit_phone1');
        const editPhone2 = document.getElementById('edit_phone2');
        const currentPicture = document.getElementById('currentPicture');
        const currentPictureContainer = document.getElementById('currentPictureContainer');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const customerId = this.getAttribute('data-id');
                
                // Set the form action
                editCustomerForm.action = `/admin/customers/${customerId}`;
                
                // Fetch customer data
                fetch(`/admin/customers/${customerId}/edit`)
                    .then(response => response.json())
                    .then(customer => {
                        editName.value = customer.name;
                        editAddress.value = customer.address || '';
                        editPhone1.value = customer.phone1 || '';
                        editPhone2.value = customer.phone2 || '';
                        
                        if (customer.picture) {
                            currentPicture.src = `/storage/${customer.picture}`;
                            currentPictureContainer.style.display = 'block';
                        } else {
                            currentPictureContainer.style.display = 'none';
                        }
                        
                        document.getElementById('remove_picture').checked = false;
                        document.getElementById('edit_picture').value = '';
                        document.getElementById('editPicturePreviewContainer').style.display = 'none';
                        
                        editCustomerModal.show();
                    });
            });
        });
        
        // Picture preview for edit modal
        const editPictureInput = document.getElementById('edit_picture');
        const editPicturePreview = document.getElementById('editPicturePreview');
        const editPicturePreviewContainer = document.getElementById('editPicturePreviewContainer');
        const editRemovePictureBtn = document.getElementById('editRemovePicture');
        
        editPictureInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    editPicturePreview.src = e.target.result;
                    editPicturePreviewContainer.style.display = 'block';
                    // Hide current picture if new one is selected
                    currentPictureContainer.style.display = 'none';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        editRemovePictureBtn.addEventListener('click', function() {
            editPictureInput.value = '';
            editPicturePreviewContainer.style.display = 'none';
            // Show current picture again if it exists
            if (currentPicture.src) {
                currentPictureContainer.style.display = 'block';
            }
        });
        
        // Handle remove picture checkbox
        document.getElementById('remove_picture').addEventListener('change', function() {
            if (this.checked) {
                currentPictureContainer.style.opacity = '0.5';
            } else {
                currentPictureContainer.style.opacity = '1';
            }
        });
    });
</script>
@endsection
