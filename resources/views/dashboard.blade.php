@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1>Dashboard</h1>
        <p class="text-muted">Quick overview of Ujuzi Shop Mall inventory activity.</p>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #007bff !important;">
                <div class="card-body">
                    <h6 class="text-muted">Total Products</h6>
                    <h2>{{ $totalProducts }}</h2>
                    <small>Products stored in the system</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #28a745 !important;">
                <div class="card-body">
                    <h6 class="text-muted">Total Stock</h6>
                    <h2>{{ $totalStock }}</h2>
                    <small>Current available quantity</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
                <div class="card-body">
                    <h6 class="text-muted">Stock Movements</h6>
                    <h2>{{ $totalMovements }}</h2>
                    <small>Stock-in and stock-out records</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted">Low Stock</h6>
                    <h2>{{ $lowStock }}</h2>
                    <small>Items with quantity 10 or below</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #6f42c1 !important;">
                <div class="card-body">
                    <h6 class="text-muted">Total Users</h6>
                    <h2>{{ $totalUsers }}</h2>
                    <small>Registered system users</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #17a2b8 !important;">
                <div class="card-body">
                    <h6 class="text-muted">Total Sales</h6>
                    <h2>UGX {{ number_format($totalSales, 2) }}</h2>
                    <small>Value of recorded sales</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3 mb-3">
        <div class="card-body">
            <h4>Current Stock by Product</h4>
            <canvas id="currentStockChart" height="100"></canvas>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4>Inventory Summary</h4>
                    <p>
                        This dashboard helps Ujuzi Shop Mall monitor products, stock levels,
                        and basic stock movement records.
                    </p>

                    <p class="mb-0 text-muted">
                        Use the product list to edit items, delete records, or update stock quantity.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4>System Features</h4>
                    <ul class="mb-0">
                        <li>Product management</li>
                        <li>Stock-in and stock-out</li>
                        <li>User login</li>
                        <li>Quantity tracking</li>
                        <li>Sales recording</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('currentStockChart'), {
            type: 'bar',
            data: {
                labels: @json($stockChartLabels),
                datasets: [{
                    label: 'Current Quantity',
                    data: @json($stockChartValues),
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Current Stock by Product'
                    }
                }
            }
        });
    </script>
@endsection
