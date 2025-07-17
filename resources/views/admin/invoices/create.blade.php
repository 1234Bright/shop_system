@extends('layouts.admin')

@section('title', 'Create New Sale')

@section('styles')
<style>
    .product-row {
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
        padding: 12px;
        position: relative;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.25rem;
    }
    
    .remove-product-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Product image container styling */
    .invoice-img-container {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .invoice-product-img {
        max-width: 35px;
        max-height: 35px;
        object-fit: contain;
    }
    
    .product-total {
        font-weight: 600;
        margin-top: 10px;
    }
    
    .summary-table th {
        font-weight: 500;
    }
    
    .summary-table .grand-total {
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    /* Product grid styling */
    .products-grid {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: #f8f9fa;
    }
    
    .product-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #dee2e6;
    }
    
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: #adb5bd;
    }
    
    .product-card.has-variants {
        border-left: 3px solid #0d6efd;
    }
    
    .product-card.selected {
        border: 2px solid #198754;
        background-color: #d1e7dd;
    }
    
    .product-img-container {
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .product-img-container img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .invoice-img-container {
        width: 35px !important;
        height: 35px !important;
        min-width: 35px !important;
        min-height: 35px !important;
        max-width: 35px !important;
        max-height: 35px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 4px;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .invoice-product-img {
        max-height: 30px !important;
        max-width: 30px !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain;
    }
    
    .product-search {
        position: sticky;
        top: 0;
        z-index: 100;
        background: white;
        padding: 10px 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Create New Sale</h1>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Invoices
        </a>
    </div>
    
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="saleForm" action="{{ route('admin.invoices.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Left Column - Available Products & Order Information -->
                    <div class="col-md-5">
                        <!-- Products List -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Available Products</h5>
                            </div>
                            <div class="card-body p-2">
                                <!-- Search box -->
                                <div class="product-search mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="productSearchInput" placeholder="Search products..." autocomplete="off">
                                    </div>
                                </div>
                                
                                <!-- Products grid -->
                                <div class="products-grid">
                                    <div class="row row-cols-2 row-cols-lg-3 g-2" id="productsGrid">
                                        @foreach($products as $product)
                                            <div class="col product-item" data-name="{{ strtolower($product->name) }}" data-code="{{ strtolower($product->product_code ?? '') }}" data-category="{{ strtolower($product->category->name ?? '') }}">
                                                <div class="card product-card h-100 {{ $product->details->count() > 0 ? 'has-variants' : '' }}" 
                                                    data-id="{{ $product->id }}" 
                                                    data-name="{{ $product->name }}" 
                                                    data-price="{{ $product->price }}" 
                                                    data-stock="{{ $product->quantity }}"
                                                    data-has-variants="{{ $product->details->count() > 0 ? 'true' : 'false' }}"
                                                    data-image="{{ $product->image }}">
                                                    <div class="card-body p-2">
                                                        <div class="product-img-container mb-2">
                                                            @if($product->image)
                                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                                                            @else
                                                                <i class="fas fa-box fa-2x text-secondary"></i>
                                                            @endif
                                                        </div>
                                                        <h6 class="card-title mb-0 small fw-bold">{{ $product->name }}</h6>
                                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                                            <span class="badge bg-primary">{{ $defaultCurrency->symbol }} {{ number_format($product->price, 2) }}</span>
                                                            <span class="badge bg-secondary small">{{ $product->quantity }}</span>
                                                        </div>
                                                        @if($product->details->count() > 0)
                                                            <div class="text-center mt-1">
                                                                <span class="badge bg-info text-dark small"><i class="fas fa-tags me-1"></i>{{ $product->details->count() }} variants</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Invoice Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Invoice Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="invoice_number" class="form-label">Invoice Number</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber }}" readonly>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="order_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="order_date" name="order_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="customer_id" class="form-label">Customer</label>
                                        <select class="form-select" id="customer_id" name="customer_id">
                                            <option value="">Walk-in Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method" required>
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="currency_id" class="form-label">Currency</label>
                                        <select class="form-select" id="currency_id" name="currency_id" required>
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}" {{ $currency->is_default ? 'selected' : '' }}>
                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Selected Products -->
                    <div class="col-md-7">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Selected Products</h5>
                            </div>
                            <div class="card-body">
                                <div id="productsContainer">
                                    <!-- Product rows will be added here dynamically -->
                                    <div class="text-center py-4 text-muted" id="noProductsMessage">
                                        <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                                        <p>No products added yet. Click on products from the left panel to add them to the invoice.</p>
                                    </div>
                                </div>
                                
                                <!-- Order Summary -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Order Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm summary-table">
                                            <tbody>
                                                <tr>
                                                    <th>Subtotal:</th>
                                                    <td class="text-end">
                                                        <span id="subtotalAmount">{{ $defaultCurrency->symbol }} 0.00</span>
                                                    </td>
                                                </tr>
                                                <tr class="grand-total">
                                                    <th>Grand Total:</th>
                                                    <td class="text-end">
                                                        <span id="grandTotalAmount">{{ $defaultCurrency->symbol }} 0.00</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">Cancel</button>
                            <button type="submit" id="completeSaleBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-check-circle me-1"></i> Complete Sale
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productSelectionModalLabel">Select Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="modalProductSearch" class="form-control" placeholder="Search products...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <div class="row" id="productSelectionTable" style="max-height: 500px; overflow-y: auto;">
                    @foreach($products as $product)
                    <div class="col-md-4 col-sm-6 mb-3 product-card" data-product-id="{{ $product->id }}">
                        <div class="card h-100">
                            <div class="card-img-top text-center pt-2" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                        style="max-height: 70px; max-width: 80%; object-fit: contain;">
                                @else
                                    <div class="no-image-placeholder" style="width: 70px; height: 70px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                <p class="text-muted small mb-1">{{ $product->category->name ?? 'N/A' }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">{{ $defaultCurrency->symbol }} {{ number_format($product->price, 2) }}</span>
                                    <span class="badge bg-secondary">Stock: {{ $product->quantity }}</span>
                                </div>
                                <div class="d-grid">
                                    @if($product->details->count() > 0)
                                        <button type="button" class="btn btn-sm btn-info show-variants-btn w-100"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-image="{{ $product->image ?? 'products/no-image.jpg' }}">
                                            <i class="fas fa-th-large me-1"></i> Show Variants
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary select-product w-100" 
                                            data-id="{{ $product->id }}" 
                                            data-name="{{ $product->name }}" 
                                            data-price="{{ $product->price }}" 
                                            data-stock="{{ $product->quantity }}"
                                            data-has-variants="{{ $product->details->count() > 0 ? 'true' : 'false' }}"
                                            data-image="{{ $product->image ?? 'products/no-image.jpg' }}">
                                            <i class="fas fa-plus-circle me-1"></i> Select
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Store currency symbol for JavaScript use
        const currencySymbol = '{{ $defaultCurrency->symbol }}';
        let productCounter = 0;
        let productSelectionModal;
        let variantsModal;
        
        // Initialize Bootstrap modals
        productSelectionModal = new bootstrap.Modal(document.getElementById('productSelectionModal'));
        variantsModal = new bootstrap.Modal(document.getElementById('productVariantsModal'));
        
        // Add click handlers for product cards in the main grid
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const productId = parseInt(this.dataset.id);
                const productName = this.dataset.name;
                const productPrice = parseFloat(this.dataset.price);
                const productStock = parseInt(this.dataset.stock);
                const productImage = this.dataset.image;
                const hasVariants = this.dataset.hasVariants === 'true';
                
                if (hasVariants) {
                    // Show variants modal
                    showProductVariants(productId, productName, productImage);
                } else {
                    // Add product directly to invoice
                    addProductToForm(productId, null, productName, productPrice, productStock, productImage);
                }
            });
        });
        
        // Search in modal
        document.getElementById('modalProductSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('#productSelectionTable .product-card');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.parentElement.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Search products in the main grid
        document.getElementById('productSearchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.product-item');
            
            // First, fetch all product variants if we haven't already
            if (!window.allProductVariants) {
                window.allProductVariants = {};
                // We'll load variants data for all products with variants
                document.querySelectorAll('.product-card[data-has-variants="true"]').forEach(card => {
                    const productId = card.dataset.id;
                    if (productId) {
                        fetch(`{{ url('admin/get-product-variants') }}/${productId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.variants) {
                                    window.allProductVariants[productId] = data.variants;
                                }
                            })
                            .catch(error => console.error('Error fetching variants for search:', error));
                    }
                });
            }
            
            // Now search through products
            items.forEach(item => {
                const productId = item.querySelector('.product-card').dataset.id;
                const productName = item.dataset.name || '';
                const productCode = item.dataset.code || '';
                const productCategory = item.dataset.category || '';
                let matchFound = false;
                
                // Check main product data
                if (productName.includes(searchTerm) || 
                    productCode.includes(searchTerm) || 
                    productCategory.includes(searchTerm)) {
                    matchFound = true;
                }
                
                // Check variants if this product has them and we've loaded them
                if (!matchFound && window.allProductVariants && window.allProductVariants[productId]) {
                    const variants = window.allProductVariants[productId];
                    for (let i = 0; i < variants.length; i++) {
                        const variant = variants[i];
                        if ((variant.product_code && variant.product_code.toLowerCase().includes(searchTerm)) || 
                            (variant.size && variant.size.toLowerCase().includes(searchTerm)) || 
                            (variant.color && variant.color.toLowerCase().includes(searchTerm))) {
                            matchFound = true;
                            break;
                        }
                    }
                }
                
                item.style.display = matchFound ? '' : 'none';
            });
        });
        
        // Product selection handler
        document.querySelectorAll('.select-product').forEach(button => {
            button.addEventListener('click', function() {
                const productId = parseInt(this.dataset.id);
                const productName = this.dataset.name;
                const productPrice = parseFloat(this.dataset.price);
                const productStock = parseInt(this.dataset.stock);
                const productImage = this.dataset.image;
                const hasVariants = this.dataset.hasVariants === 'true';
                
                if (hasVariants) {
                    // Show variants modal
                    showProductVariants(productId, productName, productImage);
                } else {
                    // Add product directly to invoice
                    addProductToForm(productId, null, productName, productPrice, productStock, productImage);
                }
            });
        });
        
        // Function to show product variants
        function showProductVariants(productId, productName, productImage) {
            // Update the modal title with the product name
            document.getElementById('productVariantsModal').querySelector('.product-name-title').textContent = `Variants of ${productName}`;
            
            // Show the loading indicator
            const variantCardsContainer = document.getElementById('variantCards');
            variantCardsContainer.innerHTML = `
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading variants...</p>
                </div>
            `;
            
            // Show the variants modal
            const variantsModal = new bootstrap.Modal(document.getElementById('productVariantsModal'));
            variantsModal.show();
            
            // Load variants via AJAX
            fetch(`{{ url('admin/get-product-variants') }}/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let variantsHtml = '';
                        
                        if (data.variants.length === 0) {
                            variantsHtml = `
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted">No variants found for this product</p>
                                </div>
                            `;
                        } else {
                            data.variants.forEach(variant => {
                                // Get the main product image from the product data or use a placeholder
                                const productImageUrl = data.product.image 
                                    ? "{{ asset('storage/') }}" + "/" + data.product.image 
                                    : "{{ asset('storage/products/no-image.jpg') }}";
                                const variantSizeText = variant.size !== 'N/A' ? variant.size : '';
                                const variantColorText = variant.color !== 'N/A' ? variant.color : '';
                                const separator = variantSizeText && variantColorText ? ', ' : '';
                                
                                variantsHtml += `
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-img-top text-center pt-2" style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                                <img src="${productImageUrl}" alt="${data.product.name}" 
                                                    style="max-height: 50px; max-width: 80%; object-fit: contain;"
                                                    onerror="this.onerror=null; this.src='{{ asset('storage/products/no-image.jpg') }}'">
                                            </div>
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="card-title mb-0 small">${variant.product_code}</h6>
                                                    <span class="badge bg-primary">${currencySymbol} ${parseFloat(variant.price).toFixed(2)}</span>
                                                </div>
                                                <div class="variant-details mb-2">
                                                    <p class="text-muted mb-1 small">
                                                        ${variantSizeText}${separator}${variantColorText}
                                                    </p>
                                                    <p class="mb-0"><span class="badge bg-secondary small">Stock: ${variant.quantity}</span></p>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-sm btn-success variant-select-btn" 
                                                        data-product-id="${data.product.id}"
                                                        data-product-name="${data.product.name}"
                                                        data-product-image="${data.product.image}"
                                                        data-variant-id="${variant.id}"
                                                        data-variant-code="${variant.product_code}"
                                                        data-variant-size="${variant.size}"
                                                        data-variant-color="${variant.color}"
                                                        data-variant-price="${variant.price}"
                                                        data-variant-stock="${variant.quantity}">
                                                        <i class="fas fa-plus-circle me-1"></i> Select
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        
                        variantCardsContainer.innerHTML = variantsHtml;
                        
                        // Add event listener for variant search
                        document.getElementById('variantSearchInput').addEventListener('keyup', function() {
                            const searchTerm = this.value.toLowerCase();
                            document.querySelectorAll('#variantCards .col-md-4').forEach(variantCard => {
                                const variantText = variantCard.textContent.toLowerCase();
                                const variantCode = variantCard.querySelector('.variant-select-btn').dataset.variantCode?.toLowerCase() || '';
                                const variantSize = variantCard.querySelector('.variant-select-btn').dataset.variantSize?.toLowerCase() || '';
                                const variantColor = variantCard.querySelector('.variant-select-btn').dataset.variantColor?.toLowerCase() || '';
                                
                                if (variantText.includes(searchTerm) || 
                                    variantCode.includes(searchTerm) ||
                                    variantSize.includes(searchTerm) ||
                                    variantColor.includes(searchTerm)) {
                                    variantCard.style.display = '';
                                } else {
                                    variantCard.style.display = 'none';
                                }
                            });
                        });
                        
                        // Clear any previous search term when opening the modal
                        document.getElementById('variantSearchInput').value = '';
                        
                        // Add event listeners to the variant select buttons
                        document.querySelectorAll('.variant-select-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const productId = this.dataset.productId;
                                const productName = this.dataset.productName;
                                const productImage = this.dataset.productImage;
                                const variantId = this.dataset.variantId;
                                const variantCode = this.dataset.variantCode;
                                const variantSize = this.dataset.variantSize;
                                const variantColor = this.dataset.variantColor;
                                const variantPrice = parseFloat(this.dataset.variantPrice);
                                const variantStock = parseInt(this.dataset.variantStock);
                                
                                // Format variant details for display
                                const variantInfo = `${variantSize !== 'N/A' ? variantSize : ''}${variantSize !== 'N/A' && variantColor !== 'N/A' ? ', ' : ''}${variantColor !== 'N/A' ? variantColor : ''}`;
                                const displayName = `${productName} (${variantInfo.trim() || 'Variant'} - ${variantCode})`;
                                
                                // Get the proper image path directly from the API response
                                // The controller returns this in the data.product.image field
                                const productImagePath = data.product.image;
                                
                                addProductToForm(productId, variantId, displayName, variantPrice, variantStock, productImagePath);
                                document.getElementById('productVariantsModal').querySelector('.btn-close').click();
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading variants:', error);
                    variantCardsContainer.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <p class="text-danger">Error loading variants</p>
                        </div>
                    `;
                });
        }
        
        // Function to add a product row to the form with image
        function addProductToForm(productId, productDetailId, productName, productPrice, maxStock, productImage) {
            // Hide the no products message
            document.getElementById('noProductsMessage').style.display = 'none';
            document.getElementById('completeSaleBtn').disabled = false;
            
            productCounter++;
            const productRow = document.createElement('div');
            productRow.className = 'product-row';
            
            // Create image HTML with proper error handling
            let imageHtml = '';
            
            // Debug the image path
            console.log('Product image path received:', productImage);
            
            if (productImage && productImage !== 'undefined' && productImage !== 'null') {
                // Make the image HTML with direct image tag to avoid template issues
                imageHtml = `<div class="invoice-img-container me-2">
                    <img 
                        src="{{ asset('storage') }}/${productImage}" 
                        class="invoice-product-img" 
                        alt="${productName}"
                        style="max-height: 35px; max-width: 35px; object-fit: contain;"
                        onerror="this.onerror=null; this.src='{{ asset('storage/products/no-image.jpg') }}';"
                    >
                </div>`;
            } else {
                // Fallback to placeholder icon
                imageHtml = `<div class="invoice-img-container me-2">
                    <i class="fas fa-box text-secondary" style="font-size: 20px;"></i>
                </div>`;
            }
            
            productRow.innerHTML = `
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">
                    <i class="fas fa-times"></i>
                </button>
                <input type="hidden" name="products[${productCounter}][product_id]" value="${productId}">
                <input type="hidden" name="products[${productCounter}][product_detail_id]" value="${productDetailId || ''}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <div class="d-flex align-items-center">
                            ${imageHtml}
                            <input type="text" class="form-control form-control-sm" value="${productName}" readonly style="font-size: 0.9rem;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">${currencySymbol}</span>
                            <input type="number" class="form-control product-price" name="products[${productCounter}][price]" value="${productPrice.toFixed(2)}" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control product-quantity" name="products[${productCounter}][quantity]" value="1" min="1" max="${maxStock}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total</label>
                        <div class="input-group">
                            <span class="input-group-text">${currencySymbol}</span>
                            <input type="text" class="form-control product-row-total" value="${productPrice.toFixed(2)}" readonly>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('productsContainer').appendChild(productRow);
            
            // Add remove button event handler
            productRow.querySelector('.remove-product-btn').addEventListener('click', function() {
                productRow.remove();
                if (document.querySelectorAll('.product-row').length === 0) {
                    document.getElementById('noProductsMessage').style.display = 'block';
                    document.getElementById('completeSaleBtn').disabled = true;
                }
                updateOrderSummary();
            });
            
            // Add quantity change event handler
            const quantityInput = productRow.querySelector('.product-quantity');
            const priceInput = productRow.querySelector('.product-price');
            const rowTotalInput = productRow.querySelector('.product-row-total');
            
            function updateRowTotal() {
                const quantity = parseInt(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                rowTotalInput.value = total.toFixed(2);
                updateOrderSummary();
            }
            
            quantityInput.addEventListener('input', updateRowTotal);
            priceInput.addEventListener('input', updateRowTotal);
            
            updateOrderSummary();
        }
        
        // Function to update the order summary
        function updateOrderSummary() {
            let subtotal = 0;
            
            // Calculate subtotal from all product rows
            document.querySelectorAll('.product-row-total').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            
            // Update summary display
            document.getElementById('subtotalAmount').textContent = `${currencySymbol} ${subtotal.toFixed(2)}`;
            document.getElementById('grandTotalAmount').textContent = `${currencySymbol} ${subtotal.toFixed(2)}`;
        }
        
        // Form validation before submit
        document.getElementById('saleForm').addEventListener('submit', function(event) {
            const productRows = document.querySelectorAll('.product-row');
            
            if (productRows.length === 0) {
                event.preventDefault();
                alert('Please add at least one product to the sale.');
                return false;
            }
            
            // Validate all product quantities
            let isValid = true;
            productRows.forEach(row => {
                const quantityInput = row.querySelector('.product-quantity');
                const quantity = parseInt(quantityInput.value);
                const maxStock = parseInt(quantityInput.getAttribute('max'));
                
                if (quantity <= 0 || quantity > maxStock) {
                    isValid = false;
                    quantityInput.classList.add('is-invalid');
                } else {
                    quantityInput.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                event.preventDefault();
                alert('Please check product quantities. They must be greater than 0 and not exceed available stock.');
                return false;
            }
        });
        
        // Auto-dismiss alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000);
        });
    });
</script>

<!-- Product Variants Modal -->
<div class="modal fade" id="productVariantsModal" tabindex="-1" aria-labelledby="productVariantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productVariantsModalLabel">Product <span class="product-name-title"></span> Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search box for variants -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="variantSearchInput" placeholder="Search variants by code, size, color..." autocomplete="off">
                    </div>
                </div>
                <div id="variantCards" class="row">
                    <!-- Variant cards will be loaded here via AJAX -->
                    <div class="col-12 text-center py-4" id="variants-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading variants...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-bs-target="#productSelectionModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Products</button>
            </div>
        </div>
    </div>
</div>
@endsection
