@extends('layouts.admin')

@section('title', 'Edit Currency')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Currency</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}">Currencies</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Edit Currency: {{ $currency->name }}
                </div>
                <a href="{{ route('admin.currencies.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Currency Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $currency->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">e.g., US Dollar, Euro, British Pound</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="symbol" class="form-label">Currency Symbol</label>
                            <input type="text" name="symbol" id="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol', $currency->symbol) }}" required>
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">e.g., $, €, £</small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1" {{ old('is_default', $currency->is_default) ? 'checked' : '' }}>
                            <label for="is_default" class="form-check-label">Set as Default Currency</label>
                            <div class="form-text">If checked, this will become the default currency for the entire system.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Currency
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
