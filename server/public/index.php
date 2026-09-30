<?php

require __DIR__ . '/../vendor/autoload.php';

use Thrivecart\Server\Action\CartController;
use Thrivecart\Server\Action\ProductController;
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

$router->get('/api/products', [new ProductController(), 'index']);

$router->post('/api/cart/calculate', [new CartController(), 'calculate']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
