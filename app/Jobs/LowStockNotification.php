<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LowStockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        $adminEmail = 'pmd150@gmail.com'; 
        Mail::raw("Product '{$this->product->name}' is running low on stock. Current stock: {$this->product->stock_quantity}", function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                    ->subject('Low Stock Alert');
        });
    }
}
