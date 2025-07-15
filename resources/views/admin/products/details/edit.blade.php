@extends('layouts.admin')

@section('title', 'Edit Product Variant - ' . $product->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0">Edit Product Variant</h1>
        <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Variants
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Product: {{ $product->name }} <span class="badge bg-light text-dark ms-2">{{ $product->product_code }}</span></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.details.update', [$product->id, $productDetail->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Product Variant Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" value="{{ $productDetail->product_code }}" disabled readonly>
                            </div>
                            <div class="form-text">The product variant code cannot be changed.</div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="size_id" class="form-label">Size/Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('size_id') is-invalid @enderror" id="size_id" name="size_id" required>
                                <option value="">Select Size/Type</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ old('size_id', $productDetail->size_id) == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="color" class="form-label">Color (Optional)</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $productDetail->color) }}" placeholder="e.g. Red, Blue, Black">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $productDetail->quantity) }}" min="0" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $productDetail->price) }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.products.details.index', $product->id) }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Variant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
