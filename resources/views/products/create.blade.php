@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Product</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" class="form-control">
                <option value="">Select category</option>
                @foreach(['Food', 'Beverages', 'Electronics', 'Clothing', 'Household', 'Other'] as $category)
                    <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="form-text text-muted">Optional. JPG, JPEG, PNG or WebP, up to 2 MB.</small>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label>Opening Quantity</label>
            <input type="number" min="0" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
        </div>

        <button class="btn btn-primary">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
