<?php

declare(strict_types=1);

use PhpAmqpLib\Message\AMQPMessage;


/**
 * @param \Curl\MultiCurl $multi_curl
 * @return void
 */
function setSettings(Curl\MultiCurl &$multi_curl): void
{
    global $cookies;

    $names = [
        'libwww-perl',
        'Presto',
        'Opera',
        'Mozilla',
        'AppleWebKit',
    ];
    $user_agent = "{$names[mt_rand(0, \count($names) - 1)]}/" . mt_rand(1,9) . "." . mt_rand(1,9) . " (compatible; MSIE " . mt_rand(1,9) . "." . mt_rand(1,9) . "; Windows NT " . mt_rand(1,9) . "." . mt_rand(1,9) . "; WOW " . mt_rand(1,9) . "; Trident/" . rand(1,9) . "." . mt_rand(1,9) . "; SLCC2; .NET CLR " . mt_rand(1,9) . "." . mt_rand(1,9) . "." . mt_rand(1,9) . "; .NET CLR " . mt_rand(1,9) . "." . mt_rand(1,9) . "." . mt_rand(1,9) . "; .NET CLR " . mt_rand(1,9) . "." . mt_rand(1,9) . "." . mt_rand(1,9) . "; Media Center PC " . mt_rand(1,9) . "." . mt_rand(1,9) . "; msn OptimizedIE" . mt_rand(1,9) . ";ZHCN)";
    $multi_curl->setUserAgent($user_agent);


    $cookie_for_pack = array_shift($cookies);
    $multi_curl->setCookies($cookie_for_pack);
    $cookies[] = $cookie_for_pack;
}

/**
 * @param string $url
 * @return string 
 */
function getRelativeLink(string $url): string
{
    try {
        $url_parts = parse_url($url);
        if (!isset($url_parts['path']) or !isset($url_parts['query'])) {
            throw new \Exception();
        }
        
        $relative_link = sprintf('%s?%s', $url_parts['path'], $url_parts['query']);
    } catch (\Exception $e) {
        return $url;
    }

    return $relative_link;
}

/**
 * @param $instance
 * @return void
 */
function success($instance)
{
}

/**
 * @param $instance
 * @return void
 */
function error($instance)
{
    global $errors;

    $errors[] = [
        'code' => $instance->errorCode,
        'url' => getRelativeLink($instance->getUrl()),
        'message' => $instance->errorMessage,
    ];
}

/**
 * @param $data
 * @return void
 */
function sendDataInRabbitMQ($data): void
{
    global $channel;

    $msg = new AMQPMessage(json_encode($data), [
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]);
    
    $channel->basic_publish($msg, '', 'fatsecret');
}

/**
 * Получить id которые ещё не были обработаны
 *
 * @param int $start
 * @param int $end
 * @param $db
 * @return array
 * @throws Exception
 */
function getUnverifiedRid(int $start, int $end, $db):array {

    $ids = $db->query("SELECT `r_id` FROM `products` WHERE `r_id` >= $start AND `r_id` <= $end 
                UNION
                SELECT `r_id` FROM `errors` WHERE `r_id` >= $start AND `r_id` <= $end");

    if ($ids == false) {
        throw new \Exception("Произошла ошибка при выполнении запроса", 1);
    }

    $ids = array_column($ids->fetchAll(), 'id');
    return array_diff(range($start, $end), $ids);
}