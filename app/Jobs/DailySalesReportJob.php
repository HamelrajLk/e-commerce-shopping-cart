<?php

namespace App\Jobs;

use App\Mail\DailySalesReportMail;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class DailySalesReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sales = OrderItem::whereDate('created_at', today())
            ->with('product')
            ->get()
            ->groupBy('product_id');

        if ($sales->isEmpty()) {
            return;
        }

        Mail::to(config('mail.admin_email'))
            ->send(new DailySalesReportMail($sales));
    }
}
