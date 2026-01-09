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
use Stripe\Stripe;
use Stripe\PaymentIntent;

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

    public function createStripeIntent(Request $request)
    {
        $user = auth()->user();
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'error' => 'Cart is empty'
            ], 400);
        }
        $totalAmount = $cartItems->sum(fn ($i) =>
            $i->quantity * $i->product->price
        );
        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::create([
            'amount' => (int) round($totalAmount * 100), 
            'currency' => 'inr',
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);
        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }
    

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cod,stripe',
            'payment_intent_id' => 'required_if:payment_method,stripe|string',
        ]);
        $user = Auth::user();
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
        if ($cartItems->isEmpty()) {
            return back()->withErrors('Cart is empty');
        }
        $totalAmount = $cartItems->sum(fn ($i) =>
            $i->quantity * $i->product->price
        );    
        if ($request->payment_method === 'stripe') {
            Stripe::setApiKey(config('services.stripe.secret'));    
            $intent = PaymentIntent::retrieve(
                $request->payment_intent_id
            );    
            if (!in_array($intent->status, ['succeeded', 'processing'])) {
                return back()->withErrors('Payment not completed.');
            }    
            if ($intent->currency !== 'inr') {
                return back()->withErrors('Invalid payment currency.');
            }
        }        
        $order = null; 
        DB::transaction(function () use (
            $cartItems,
            $user,
            $request,
            $totalAmount,
            &$order 
        ) {
            $order = Order::create([
                'user_id'        => $user->id,
                'total_amount'   => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'stripe'
                    ? 'paid'
                    : 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
                $product = $item->product;
                $product->decrement('stock_quantity', $item->quantity);
                if ($product->stock_quantity <= $product->alert_qty) {
                    LowStockNotification::dispatch($product);
                }
            }
            Cart::where('user_id', $user->id)->delete();
        });   
        return redirect()->route('order.success', $order->id);
    }
    public function success(Order $order)
    {   
        abort_if($order->user_id !== Auth::id(), 403);     
        $order->load('items.product'); 
        return Inertia::render('Checkout/Success', [
            'order' => [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'items' => $order->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity,
                ]),
            ],
        ]);
    }

}
