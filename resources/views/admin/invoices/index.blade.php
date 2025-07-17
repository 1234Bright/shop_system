@extends('layouts.admin')

@section('title', 'Invoice Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Sales & Invoices</h1>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-cash-register me-2"></i>New Sale
        </a>
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
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Recent Sales</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="invoiceSearch" class="form-control" placeholder="Search invoices..." aria-label="Search invoices">
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
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceTableBody">
                        @forelse($invoices as $invoice)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $invoice->invoice_number }}</span></td>
                            <td>{{ $invoice->order_date->format('M d, Y') }}</td>
                            <td>{{ $invoice->customer->name ?? 'Walk-in Customer' }}</td>
                            <td>
                                @if($invoice->payment_method == 'cash')
                                    <span class="badge bg-success">Cash</span>
                                @elseif($invoice->payment_method == 'bank')
                                    <span class="badge bg-info">Bank</span>
                                @else
                                    <span class="badge bg-warning text-dark">Cheque</span>
                                @endif
                            </td>
                            <td>{{ $invoice->currency->symbol }} {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ $invoice->currency->name }}</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.invoices.print', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if($invoice->status != 'cancelled')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelInvoiceModal{{ $invoice->id }}" title="Cancel">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Cancel Invoice Modal -->
                        @if($invoice->status != 'cancelled')
                        <div class="modal fade" id="cancelInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="cancelInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancelInvoiceModalLabel{{ $invoice->id }}">Cancel Invoice</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to cancel invoice <strong>#{{ $invoice->invoice_number }}</strong>? This will restore all product quantities back to inventory.
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
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-receipt fa-3x text-muted mb-2"></i>
                                    <h5 class="text-muted">No invoices found</h5>
                                    <p class="text-muted">Create your first sale by clicking the 'New Sale' button</p>
                                </div>
                            </td>
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
        const searchInput = document.getElementById('invoiceSearch');
        const tableRows = document.querySelectorAll('#invoiceTableBody tr');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(function(row) {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
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
@endsection
