<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Product $product)
    {
        return view('stock.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable',
        ]);

        if ($request->type == 'stock_out' && $request->quantity > $product->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        if ($request->type == 'stock_in') {
            $product->quantity += $request->quantity;
        } else {
            $product->quantity -= $request->quantity;
        }

        $product->save();

        StockMovement::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'note' => $request->note,
        ]);

        return redirect()->route('products.index')->with('success', 'Stock updated successfully.');
    }
}