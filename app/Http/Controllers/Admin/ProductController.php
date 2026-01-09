<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:1',
            'alert_qty' => 'required|integer|min:1',
            'status'          => 'required|boolean',   
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }
    
        Product::create($validated);

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:1',
            'alert_qty' => 'required|integer|min:1',
            'status'          => 'required|boolean'           
        ]);    
        $validated['status'] = (bool) $request->status;     

       if ($request->hasFile('image')) {         
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }       
        $validated['image'] = $request->file('image')->store('products', 'public');
    }
        $product->update($validated);

    return redirect()
        ->route('admin.products')
        ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {       
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }   
    $product->delete();
        return Inertia::location(route('admin.products'));        
    }
}


