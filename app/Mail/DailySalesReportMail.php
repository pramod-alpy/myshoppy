<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;



class DailySalesReportMail extends Mailable
{
    public $report;
    public $totalSales;
    public $reportDate;

    public function __construct($report, $totalSales)
    {
        $this->report = $report;
        $this->totalSales = $totalSales;
        $this->reportDate = Carbon::now()->format('d-m-Y');
    }

    public function build()
    {
        return $this->subject('Daily Sales Report as on ' . $this->reportDate)
                    ->view('emails.daily_sales_report');
    }
}
