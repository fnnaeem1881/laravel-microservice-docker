<?php

namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The message to send.
     *
     * @var string
     */
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $connection = app('rabbitmq')->connection();
        $channel = $connection->channel();
        $channel->queue_declare('test_queue', false, true, false, false);
        $msg = new \PhpAmqpLib\Message\AMQPMessage($this->message);
        $channel->basic_publish($msg, '', 'test_queue');
        $channel->close();
        $connection->close();
    }
}
