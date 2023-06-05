<?php

use App\Jobs\AuthenticationJob;
use App\Jobs\SendMessageJob;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/send-message', function () {
    for ($x = 0; $x <= 200; $x++) {
        AuthenticationJob::dispatch($x.' data');

      }
    // return response()->json(['status' => 'success']);


    return response()->json(['status' => 'success']);
});
Route::get('/check-connect', function () {

    // require_once __DIR__ . '/vendor/autoload.php'; // Replace with the path to your autoload file


    // Replace these values with your own
    // $host = 'goose-01.rmq2.cloudamqp.com';
    // $port = 5672;
    // $user = 'xrljngis';
    // $password = '4tdn42J7Dpq4oXL9QH-IIrU4WkyPfSuE';
    // $vhost = 'xrljngis';
    $host = 'rabbitmq'; // replace with your container name
    $port = 5672;
    $user = 'guest';
    $password = 'guest';
    $vhost = '/';
    try {
        $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
        $channel = $connection->channel();
        echo "Connected to RabbitMQ successfully.\n";
        $channel->close();
        $connection->close();
    } catch (Exception $e) {
        echo "Failed to connect to RabbitMQ: " . $e->getMessage() . "\n";
    }
});
Route::get('/send/{p}', function ($p) {



    // Replace these values with your own
    // $host = 'goose-01.rmq2.cloudamqp.com';
    // $port = 5672;
    // $user = 'xrljngis';
    // $password = '4tdn42J7Dpq4oXL9QH-IIrU4WkyPfSuE';
    // $vhost = 'xrljngis';
    $host = 'rabbitmq'; // replace with your container name
    $port = 5672;
    $user = 'guest';
    $password = 'guest';
    $vhost = '/';
    $exchange = 'my_exchange'; // replace with your own exchange name
    $queue = 'default'; // replace with your own queue name
    $messageBody = $p; // replace with your own message body

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
    return response()->json(['status' => 'success']);

    });
require __DIR__ . '/auth.php';
