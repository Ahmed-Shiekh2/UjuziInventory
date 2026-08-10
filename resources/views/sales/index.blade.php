@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sales</h2>

    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">Create Sale</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Phone Number</th>
                    <th>Transaction Reference</th>
                    <th>Payment Status</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    @php($item = $sale->items->first())
                    <tr>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $item && $item->product ? $item->product->name : 'Deleted product' }}</td>
                        <td>{{ $item ? $item->quantity : '—' }}</td>
                        <td>{{ $item ? 'UGX ' . number_format($item->unit_price, 2) : '—' }}</td>
                        <td>UGX {{ number_format($sale->total_amount, 2) }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}</td>
                        <td>{{ $sale->phone_number ?: '—' }}</td>
                        <td>{{ $sale->transaction_reference ?: '—' }}</td>
                        <td>{{ ucfirst($sale->payment_status) }}</td>
                        <td>{{ optional($sale->user)->name ?: 'Deleted user' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">No sales found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sales->links('pagination::bootstrap-4') }}
</div>
@endsection
