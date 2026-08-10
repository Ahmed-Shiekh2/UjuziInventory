@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Sale</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        <div class="form-group">
            <label>Product</label>
            <select name="product_id" class="form-control" required>
                <option value="">Select product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ (string) old('product_id') === (string) $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->sku }}) — {{ $product->quantity }} available — UGX {{ number_format($product->price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
        </div>

        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="mtn_mobile_money" {{ old('payment_method') === 'mtn_mobile_money' ? 'selected' : '' }}>MTN Mobile Money</option>
                <option value="airtel_money" {{ old('payment_method') === 'airtel_money' ? 'selected' : '' }}>Airtel Money</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
            <small class="form-text text-muted">Required for MTN Mobile Money and Airtel Money.</small>
        </div>

        <div class="form-group">
            <label>Transaction Reference</label>
            <input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}">
            <small class="form-text text-muted">Optional for this coursework version.</small>
        </div>

        <button class="btn btn-primary">Record Sale</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
