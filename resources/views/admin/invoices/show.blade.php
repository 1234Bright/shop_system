@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Invoice #{{ $invoice->invoice_number }}</h1>
        <div>
            <a href="{{ route('admin.invoices.print', $invoice->id) }}" class="btn btn-primary me-2" target="_blank">
                <i class="fas fa-print me-2"></i>Print Invoice
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Invoices
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <!-- Invoice Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Invoice Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th style="width: 40%">Invoice Number:</th>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $invoice->order_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($invoice->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td>
                                    @if($invoice->payment_method == 'cash')
                                        <span class="badge bg-success">Cash</span>
                                    @elseif($invoice->payment_method == 'bank')
                                        <span class="badge bg-info">Bank Transfer</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Cheque</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Currency:</th>
                                <td>{{ $invoice->currency->name }} ({{ $invoice->currency->symbol }})</td>
                            </tr>
                            @if($invoice->notes)
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $invoice->notes }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    @if($invoice->customer)
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 40%">Name:</th>
                                    <td><strong>{{ $invoice->customer->name }}</strong></td>
                                </tr>
                                @if($invoice->customer->address)
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $invoice->customer->address }}</td>
                                </tr>
                                @endif
                                @if($invoice->customer->phone1)
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $invoice->customer->phone1 }}</td>
                                </tr>
                                @endif
                                @if($invoice->customer->phone2)
                                <tr>
                                    <th>Alternative Phone:</th>
                                    <td>{{ $invoice->customer->phone2 }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Walk-in Customer</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Invoice Summary -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Invoice Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column h-100">
                        <div class="flex-grow-1">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%">Items:</th>
                                        <td>{{ $invoice->details->count() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Quantity:</th>
                                        <td>{{ $invoice->details->sum('quantity') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="alert alert-success mb-0 text-center">
                                <h5 class="mb-0">Total Amount</h5>
                                <h3 class="mb-0">{{ $invoice->currency->symbol }} {{ number_format($invoice->total_amount, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Invoice Items -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Invoice Items</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->product->name }}</td>
                            <td>
                                @if($detail->productDetail)
                                    <span class="badge bg-light text-dark">{{ $detail->productDetail->product_code }}</span>
                                    <small>
                                        {{ $detail->productDetail->size ? $detail->productDetail->size->name : '' }}
                                        {{ $detail->productDetail->size && $detail->productDetail->color ? ',' : '' }}
                                        {{ $detail->productDetail->color ?? '' }}
                                    </small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-end">{{ $invoice->currency->symbol }} {{ number_format($detail->price, 2) }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-end">{{ $invoice->currency->symbol }} {{ number_format($detail->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                            <td class="text-end"><strong>{{ $invoice->currency->symbol }} {{ number_format($invoice->total_amount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mt-3">
        @if($invoice->status != 'cancelled')
        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancelInvoiceModal">
            <i class="fas fa-ban me-1"></i> Cancel Invoice
        </button>
        @endif
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">Back to Invoices</a>
    </div>
    
    <!-- Cancel Invoice Modal -->
    @if($invoice->status != 'cancelled')
    <div class="modal fade" id="cancelInvoiceModal" tabindex="-1" aria-labelledby="cancelInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelInvoiceModalLabel">Cancel Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel invoice <strong>#{{ $invoice->invoice_number }}</strong>?</p>
                    <p>This will return all products to inventory and mark the invoice as cancelled.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Cancel Invoice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts
        const alerts = document.querySelectorAll('.alert-dismissible');
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
@endsection
