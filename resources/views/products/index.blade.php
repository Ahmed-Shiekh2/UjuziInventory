@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ujuzi Shop Mall Inventory</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('products.index') }}" class="form-inline mb-3">
        <label for="search" class="sr-only">Search Products</label>
        <input id="search" type="search" name="search" class="form-control mr-2" value="{{ $search }}" placeholder="Search Products">
        <button type="submit" class="btn btn-primary mr-2">Search</button>
        @if($search)
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Current Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image_path)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->description ?: '—' }}</td>
                        <td>{{ $product->category ?: '—' }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('stock.create', $product) }}" class="btn btn-sm btn-success">Stock Update</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links('pagination::bootstrap-4') }}
</div>
@endsection
