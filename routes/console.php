<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendDailySalesReportJob;

Schedule::job(new SendDailySalesReportJob)
    ->dailyAt('20:00'); // 8 PM

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
