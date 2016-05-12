<?php
/**
 * User: boshurik
 * Date: 06.05.16
 * Time: 13:10
 */

require_once __DIR__ . '/../vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$application = new BoShurik\WebSocket\Application();

/**
 * RabbitMQ
 */
$client = new \Bunny\Async\Client($loop);
$client->connect()->then(function(\Bunny\Async\Client $client){
    return $client->channel();
})->then(function(\Bunny\Channel $channel){
    return \React\Promise\all(array(
        $channel,
        $channel->queueDeclare("queue"),
        $channel->exchangeDeclare("exchange"),
        $channel->queueBind("queue", "exchange"),
    ));
})->then(function($data) use ($application){
    /** @var \Bunny\Channel $channel */
    $channel = $data[0];

    return $channel->consume(function (\Bunny\Message $msg, \Bunny\Channel $ch, \Bunny\Async\Client $c) use ($application) {
        $application->onMessage($msg->content);
    }, "queue", "", false, true);
});

/**
 * Socket
 */
//$socket = new React\Socket\Server($loop);
//$socket->on('connection', function ($conn) use ($application) {
//    $conn->on('data', array($application, 'onMessage'));
//});
//$socket->listen(1337);

/**
 * ZeroMQ
 */
//// Listen for the web server to make a ZeroMQ push after an ajax request
//$context = new React\ZMQ\Context($loop);
//$pull = $context->getSocket(ZMQ::SOCKET_PULL);
//$pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
//$pull->on('messa∂ge', array($application, 'onMessage'));


$app = @new \Ratchet\App('127.0.0.1', 8888, '0.0.0.0', $loop); // Binding to 0.0.0.0 means remotes can connect
$app->route('/ws', $application, array('*'));

echo "\nServer has been running\n\n";
$app->run();
