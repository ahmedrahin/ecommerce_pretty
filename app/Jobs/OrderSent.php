<?php

namespace App\Jobs;

use App\Mail\{OrderPlaced,OrderPlacedCustomer};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderSent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(config('app.email'))->send(new OrderPlaced($this->order));
        
        // Customer mail
        Mail::to($this->order->email)->send(new OrderPlacedCustomer($this->order));
    }
}
