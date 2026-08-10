<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products',
            'description' => 'nullable',
            'category' => 'nullable|in:Food,Beverages,Electronics,Clothing,Household,Other',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        unset($validated['image']);
        $validated['image_path'] = $imagePath;

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::create($validated);

                if ($validated['quantity'] > 0) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'stock_in',
                        'quantity' => $validated['quantity'],
                        'note' => 'Opening stock',
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'description' => 'nullable',
            'category' => 'nullable|in:Food,Beverages,Electronics,Clothing,Household,Other',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
        ]);

        $oldImagePath = $product->image_path;
        $newImagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        unset($validated['image']);

        if ($newImagePath) {
            $validated['image_path'] = $newImagePath;
        }

        try {
            $product->update($validated);
        } catch (\Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $imagePath = $product->image_path;
        $product->delete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
