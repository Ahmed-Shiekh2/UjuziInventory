@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stock Movements</h2>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date/time</th>
                    <th>Product name</th>
                    <th>SKU</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ optional($movement->product)->name ?: 'Deleted product' }}</td>
                        <td>{{ optional($movement->product)->sku ?: '—' }}</td>
                        <td>{{ $movement->type === 'stock_in' ? 'Stock In' : 'Stock Out' }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->note ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No stock movements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $movements->links('pagination::bootstrap-4') }}
</div>
@endsection
