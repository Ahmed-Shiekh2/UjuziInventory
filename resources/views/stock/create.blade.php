@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stock Update: {{ $product->name }}</h2>

    <p>Current Quantity: <strong>{{ $product->quantity }}</strong></p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('stock.store', $product) }}">
        @csrf

        <div class="form-group">
            <label>Stock Type</label>
            <select name="type" class="form-control" required>
                <option value="stock_in" {{ old('type') === 'stock_in' ? 'selected' : '' }}>Stock In</option>
                <option value="stock_out" {{ old('type') === 'stock_out' ? 'selected' : '' }}>Stock Out</option>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
        </div>

        <div class="form-group">
            <label>Note</label>
            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
        </div>

        <button class="btn btn-success">Update Stock</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
