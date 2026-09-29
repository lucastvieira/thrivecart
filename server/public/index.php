<?php

require __DIR__ . '/../vendor/autoload.php';

use Thrivecart\Server\Http;
use Thrivecart\Server\Response;
use Thrivecart\Server\Router;

ini_set('display_errors', '0');

set_exception_handler(static function (\Throwable $exception): void {
    Response::error($exception->getMessage(), 500);
});

$httpServer = new Http();
$httpServer->prepare();

$router = new Router();

$router->get('/api/me', function () {
    Response::json([
        'message' => 'Hello World',
    ]);
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
