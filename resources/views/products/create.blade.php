@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Product</h2>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Opening Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <button class="btn btn-primary">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection