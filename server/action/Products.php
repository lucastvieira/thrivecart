<?php

namespace Thrivecart\Server\Action;

use Thrivecart\Server\Response;
use Thrivecart\Server\Catalog;

final class Products
{
    public function execute(): void
    {
        $catalog = new Catalog();

        Response::json([
            'products' => $catalog->products(),
        ]);
    }
}
