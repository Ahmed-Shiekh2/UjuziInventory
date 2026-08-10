@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Product</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" class="form-control">
                <option value="">Select category</option>
                @foreach(['Food', 'Beverages', 'Electronics', 'Clothing', 'Household', 'Other'] as $category)
                    <option value="{{ $category }}" {{ old('category', $product->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            @if($product->image_path)
                <div class="mb-2">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @else
                <p class="text-muted mb-2">No image</p>
            @endif
            <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="form-text text-muted">Upload a new image to replace the existing one. Maximum 2 MB.</small>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label>Current Quantity</label>
            <input type="number" class="form-control" value="{{ $product->quantity }}" readonly>
            <small class="form-text text-muted">Use Stock Update to change quantity.</small>
        </div>

        <button class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
