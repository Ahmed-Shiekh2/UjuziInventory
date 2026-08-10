@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Product</h2>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label>Current Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
        </div>

        <button class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection