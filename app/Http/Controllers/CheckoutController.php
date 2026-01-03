<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Jobs\LowStockNotification;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
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
            ];
        });

        $totalPrice = $products->sum('total');

        return Inertia::render('Checkout/Index', [
            'products'    => $products,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);
    
        $user = Auth::user();
    
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
    
        if ($cartItems->isEmpty()) {
            return back()->withErrors('Cart is empty');
        }    
        DB::transaction(function () use ($cartItems, $user, $request) {   
            $order = Order::create([
                'user_id'        => $user->id,
                'total_amount'   => $cartItems->sum(fn ($i) => $i->quantity * $i->product->price),
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
            ]);              
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'  => $order->id,
                'product_id'=> $item->product_id,
                'quantity'  => $item->quantity,
                'price'     => $item->product->price,
            ]);           
            $product = $item->product;
            $product->decrement('stock_quantity', $item->quantity);
            
            if ($product->stock_quantity <= $product->alert_qty) {
                LowStockNotification::dispatch($product);
            }
        }        
        Cart::where('user_id', $user->id)->delete();
        });

        return redirect('/')->with('success', 'Order placed successfully!');
    }
}
