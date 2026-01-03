<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
   
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
    
        $user = Auth::user();
        $productId = $request->product_id;
    
       
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
    
        if ($cartItem) {           
            $cartItem->increment('quantity');
        } else {         
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }   
       
        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
    
        return response()->json([
            'message' => 'Added to cart',
            'cartCount' => $cartCount,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        $products = $cartItems->map(function ($item) {
            return [
                'id'       => $item->product->id,
                'name'     => $item->product->name,
                'price'    => (float) $item->product->price,
                'quantity' => $item->quantity,
                'total'    => $item->quantity * $item->product->price,
                'stock_quantity'    => $item->product->stock_quantity,
            ];
        });

        $totalPrice = $products->sum('total');

        return Inertia::render('Cart/Index', [
            'products'    => $products,
            'totalPrice' => $totalPrice,
        ]);
    }
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        $user = Auth::user();
    
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();
    
        if (!$cartItem) {
            return response()->json(['message' => 'Item not found'], 404);
        }
    
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);    
       
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
    
        $products = $cartItems->map(function ($item) {
            return [
                'id'       => $item->product->id,
                'name'     => $item->product->name,
                'price'    => (float) $item->product->price,
                'quantity' => $item->quantity,
                'total'    => $item->quantity * $item->product->price,
                'stock_quantity'    => $item->product->stock_quantity,
            ];
        });
    
        return response()->json([
            'products'    => $products,
            'totalPrice' => $products->sum('total'),
            'cartCount'  => $cartItems->sum('quantity'),
        ]);
    }  

    
    public function remove(Request $request, $id)
    {
        $user = Auth::user();
        Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->delete();   
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
        $products = $cartItems->map(function ($item) {
            return [
                'id'       => $item->product->id,
                'name'     => $item->product->name,
                'price'    => (float) $item->product->price,
                'quantity' => $item->quantity,
                'total'    => $item->quantity * $item->product->price,
                'stock_quantity'    => $item->product->stock_quantity,
            ];
        });
        return response()->json([
            'products'    => $products,
            'totalPrice' => $products->sum('total'),
            'cartCount'  => $cartItems->sum('quantity'),
        ]);
     }
}
