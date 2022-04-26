<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';
include __DIR__.'/data/request_data.php';
require __DIR__.'/functions.php';

use Curl\MultiCurl;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$start_time = microtime(true);

$counter = 0;
$step_outer = 100;
$end_position = ($argv[2]) * 1000000 ?? 4000;
$start_position = ($argv[1]) * 1000000 ?? 3000;
$flow_number = $argv[3] ?? 1;

if (is_readable("./statistic/$flow_number.txt")) {
    $start_position = (int)file_get_contents(__DIR__ . "/../statistic/$flow_number.txt");
}

$connection = new AMQPStreamConnection('localhost', 5673, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('queue', false, true, false, false);

$db = new PDO('mysql:host=;dbname=;charset=utf8mb4', 'login', 'password');


$multi_curl = new MultiCurl();
$multi_curl->setHeaders($headers[mt_rand(0, count($headers))]);
$multi_curl->setConcurrency = 100;
$multi_curl->setTimeout(4);
$multi_curl->success('success');
$multi_curl->error('error');

for ($i = $start_position; $i < $end_position; $i = $i + $step_outer) {
    file_put_contents(__DIR__ . "/../statistic/$flow_number.txt", $i);

    //arrange proxy for pack
    $proxy_for_pack = array_shift($proxies);
    $multi_curl->setProxies($proxy_for_pack);
    $proxies[] = $proxy_for_pack;

    try {
        $ids = getUnverifiedRid($i, $i + $step_outer, $db);
    } catch (Exception $e) {
        echo PHP_EOL . "Failed get unverified id";
        continue;
    }

    try {
        foreach ($ids as $key => $r_id) {
            $multi_curl->addGet('link', [
                'rid' => $r_id,
            ]);

            setSettings($multi_curl);
        }

        $multi_curl->start();

        sendDataInRabbitMQ([]);

        echo \PHP_EOL.\PHP_EOL."TIME - ".round(microtime(true) - $start_time, 4).\PHP_EOL.\PHP_EOL;
    } catch (\Exception $e) {
        $errors[] = [
            'code' => 1,
            'url' => 'script error',
            'message' => $e->getMessage(),
        ];
    }
}
