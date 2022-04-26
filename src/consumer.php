<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$db = new PDO('mysql:host=localhost;dbname=;charset=utf8mb4', 'login', 'password');
while (true) {
    try {
        $queue = 'queue';
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, false, false, false);
        $channel->basic_qos(null, 1 , null);

        $callback = function ($msg) {
            $array = json_decode($msg->body, true);
            extract($array);

        };

        $channel->basic_consume($queue, '', false, true, false, false, $callback);
        /**
         * @param \PhpAmqpLib\Channel\AMQPChannel $channel
         * @param \PhpAmqpLib\Connection\AbstractConnection $connection
         */
        function shutdown($channel, $connection)
        {
            $channel->close();
            $connection->close();
        }

        register_shutdown_function('shutdown', $channel, $connection);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        sleep(1);
    }
}


