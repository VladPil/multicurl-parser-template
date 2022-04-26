<?php

declare(strict_types=1);

$cookies = json_decode(file_get_contents(__DIR__.'/cookies.json'), true);

$proxies = [
    ['46.16.13.29:49155'],
];

$headers = [
    [
        'Host: www.fatsecret.ru',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
        'Referer: https://www.fatsecret.ru/Auth.aspx?pa=s',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-User: ?1',
        'Cache-Control: max-age=0',
        'TE: trailers',
    ],
    [
        'Host: www.fatsecret.ru',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding: gzip, deflate, br',
        'Referer: https://www.fatsecret.ru/Home.aspx?pa=spr',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-User: ?1',
        'Cache-Control: max-age=0',
        'TE: trailers',
    ],
];
