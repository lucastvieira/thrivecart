<?php

require __DIR__ . '/../vendor/autoload.php';

use Thrivecart\Server\Http;
use Thrivecart\Server\Router;

$httpServer = new Http();
$httpServer->prepare();

$router = new Router();

$router->get('/api/me', function () {
    echo json_encode([
        'message' => 'Hello World',
    ]);
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
