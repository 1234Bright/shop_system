@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">System Settings</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">System Settings</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
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
    
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Left column for General and Company Settings -->
            <div class="col-lg-6">
                <!-- General Settings Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cogs me-1"></i>
                            General Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="default_currency_id" class="form-label">Default Currency</label>
                            <select name="default_currency_id" id="default_currency_id" class="form-select">
                                <option value="">Select Default Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $defaultCurrency && $defaultCurrency->id == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }} ({{ $currency->symbol }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">This currency will be used throughout the system for all financial transactions.</div>
                        </div>
                    </div>
                </div>
                
                <!-- Company Settings Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-building me-1"></i>
                            Company Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', \App\Models\Setting::get('company_name')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="company_address" class="form-label">Company Address</label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ old('company_address', \App\Models\Setting::get('company_address')) }}</textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company_phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', \App\Models\Setting::get('company_phone')) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="company_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', \App\Models\Setting::get('company_email')) }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="company_logo" class="form-label">Company Logo</label>
                            <input type="file" class="form-control" id="company_logo" name="company_logo">
                            @if(\App\Models\Setting::get('company_logo'))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . \App\Models\Setting::get('company_logo')) }}" alt="Company Logo" class="img-thumbnail" style="max-height: 100px">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right column for Invoice Settings -->
            <div class="col-lg-6">
                <!-- Invoice Settings Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-invoice me-1"></i>
                            Invoice Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="invoice_footer_text" class="form-label">Invoice Footer Text</label>
                            <textarea class="form-control" id="invoice_footer_text" name="invoice_footer_text" rows="3">{{ old('invoice_footer_text', \App\Models\Setting::get('invoice_footer_text')) }}</textarea>
                            <div class="form-text">This text will appear at the bottom of all printed invoices.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">Default Tax Rate (%)</label>
                            <input type="number" step="0.01" class="form-control" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', \App\Models\Setting::get('tax_rate')) }}">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="show_tax_on_invoice" name="show_tax_on_invoice" {{ old('show_tax_on_invoice', \App\Models\Setting::get('show_tax_on_invoice')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_tax_on_invoice">Show tax on invoices</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save All Settings
            </button>
        </div>
    </form>
</div>

<script>
    // Auto-dismiss alerts after 5 seconds
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 5000);
</script>
@endsection
