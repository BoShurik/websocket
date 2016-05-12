<?php
/**
 * User: boshurik
 * Date: 06.05.16
 * Time: 13:20
 */

require_once __DIR__ . '/../vendor/autoload.php';

$data = array(
    'category' => 'category',
    'message' => uniqid(),
);

/**
 * RabbitMq
 */
$client = new \Bunny\Client();
$client->connect();
$channel = $client->channel();

$channel->queueDeclare("queue");
$channel->exchangeDeclare("exchange");
$channel->queueBind("queue", "exchange");

$channel->publish(json_encode($data), array(), 'exchange');

$client->disconnect();

/**
 * Socket
 */
//$client = stream_socket_client('tcp://127.0.0.1:1337');
//fwrite($client, json_encode($data));

/**
 * ZeroMQ
 */
//$context = new \ZMQContext();
//$socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'worker');
//$socket->connect('tcp://127.0.0.1:5555');
//$socket->send(json_encode($data));