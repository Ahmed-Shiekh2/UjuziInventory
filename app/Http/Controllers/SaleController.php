<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::with(['items.product', 'user'])
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,mtn_mobile_money,airtel_money',
            'phone_number' => 'nullable|required_unless:payment_method,cash|string|max:30',
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        $sale = DB::transaction(function () use ($validated, $request) {
            $product = Product::whereKey($validated['product_id'])->lockForUpdate()->firstOrFail();

            if ($validated['quantity'] > $product->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'The requested quantity exceeds the available stock.',
                ]);
            }

            $unitPrice = $product->price;
            $subtotal = round((float) $unitPrice * $validated['quantity'], 2);

            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'total_amount' => $subtotal,
                'payment_method' => $validated['payment_method'],
                'phone_number' => $validated['phone_number'] ?? null,
                'transaction_reference' => $validated['transaction_reference'] ?? null,
            ]);

            $sale->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $product->quantity -= $validated['quantity'];
            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'stock_out',
                'quantity' => $validated['quantity'],
                'note' => 'Sale #' . $sale->id,
            ]);

            return $sale;
        });

        return redirect()->route('sales.index')->with('success', 'Sale #' . $sale->id . ' recorded successfully.');
    }
}
