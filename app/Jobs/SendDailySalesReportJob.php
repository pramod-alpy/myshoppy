<?php

namespace App\Jobs;

use App\Mail\DailySalesReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDailySalesReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $start = now()->startOfDay();
        $end   = now()->endOfDay();
       
        $report = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'products.id', '=', 'order_items.product_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->whereBetween('orders.created_at', [$start, $end])
        ->select(
            'products.name as product_name',
            'order_items.quantity',
            'order_items.price',
            DB::raw('(order_items.quantity * order_items.price) as item_total'),
            'users.name as buyer_name',
            'orders.created_at'
        )
        ->orderBy('orders.created_at', 'desc')
        ->get();
      
        $totalSales = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');
        if ($report->isNotEmpty()) {
            Mail::to('pmd150@gmail.com')
                ->send(new DailySalesReportMail($report, $totalSales));
        }
    }
}
