<?php

use App\Jobs\DailySalesReportJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('daily:sales-report', function () {
    DailySalesReportJob::dispatch();
})->describe('Dispatch daily sales report job');

app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('daily:sales-report')->dailyAt('20:00');
});