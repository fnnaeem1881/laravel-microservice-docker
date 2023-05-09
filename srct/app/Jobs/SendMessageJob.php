<?php

namespace App\Jobs;

use App\Http\Controllers\QueueInsert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $SendBkashController =(new QueueInsert)->store($this->message);
        echo $SendBkashController;

    }
}
