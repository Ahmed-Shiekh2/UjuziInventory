<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $validated = $request->validate([
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable',
        ]);

        $updated = DB::transaction(function () use ($product, $validated) {
            $lockedProduct = Product::whereKey($product->getKey())->lockForUpdate()->firstOrFail();

            if ($validated['type'] === 'stock_out' && $validated['quantity'] > $lockedProduct->quantity) {
                return false;
            }

            $quantityChange = $validated['type'] === 'stock_in'
                ? $validated['quantity']
                : -$validated['quantity'];

            $lockedProduct->quantity += $quantityChange;
            $lockedProduct->save();

            StockMovement::create([
                'product_id' => $lockedProduct->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'note' => $validated['note'] ?? null,
            ]);

            return true;
        });

        if (! $updated) {
            return back()->with('error', 'Not enough stock available.');
        }

        return redirect()->route('products.index')->with('success', 'Stock updated successfully.');
    }
}
