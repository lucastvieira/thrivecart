<?php

namespace Thrivecart\Server\Action;

use Thrivecart\Server\Response;
use Thrivecart\Server\Catalog;
use Thrivecart\Server\Controller;

final class ProductController extends Controller
{
    public function index(): void
    {
        $catalog = new Catalog();

        Response::json([
            'products' => $catalog->products(),
        ]);
    }
}
