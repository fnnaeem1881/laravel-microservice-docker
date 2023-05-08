<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        // $host = 'goose-01.rmq2.cloudamqp.com'; // replace with your container name
        // $port = 5672;
        // $user = 'xrljngis';
        // $password = '4tdn42J7Dpq4oXL9QH-IIrU4WkyPfSu';
        // $vhost = 'xrljngis';

        $host = 'rabbitmq'; // replace with your container name
        $port = 5672;
        $user = 'guest';
        $password = 'guest';
        $vhost = '/';

        $exchange = 'my_exchange'; // replace with your own exchange name
        $queue = 'default'; // replace with your own queue name
        $messageBody = $this->message;

        // create a connection to RabbitMQ
        $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);

        // create a channel
        $channel = $connection->channel();

        // declare an exchange
        $channel->exchange_declare($exchange, 'direct', false, true, false);

        // declare a queue
        $channel->queue_declare($queue, false, true, false, false);

        // bind the queue to the exchange
        $channel->queue_bind($queue, $exchange);

        // create a message
        $message = new AMQPMessage($messageBody);

        // publish the message to the exchange
        $channel->basic_publish($message, $exchange);

        // close the channel and the connection
        $channel->close();
        $connection->close();

    }
}
