<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $movements = StockMovement::with('product')
            ->latest()
            ->paginate(15);

        return view('stock_movements.index', compact('movements'));
    }
}
