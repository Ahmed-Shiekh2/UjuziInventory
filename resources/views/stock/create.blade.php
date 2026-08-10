@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stock Update: {{ $product->name }}</h2>

    <p>Current Quantity: <strong>{{ $product->quantity }}</strong></p>

    <form method="POST" action="{{ route('stock.store', $product) }}">
        @csrf

        <div class="form-group">
            <label>Stock Type</label>
            <select name="type" class="form-control" required>
                <option value="stock_in">Stock In</option>
                <option value="stock_out">Stock Out</option>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label>Note</label>
            <textarea name="note" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Update Stock</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection