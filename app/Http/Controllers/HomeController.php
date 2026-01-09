<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status', true)->where('stock_quantity', '>', 0)->get();
        $cartItems = Auth::check()
            ? Cart::where('user_id', Auth::id())
                ->get()
                ->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                    ];
                })
            : [];

        return Inertia::render('Home', [
            'products'  => $products,
            'cartItems' => $cartItems, 
        ]);
    }
}

