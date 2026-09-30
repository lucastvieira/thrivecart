<?php

namespace Thrivecart\Server\Service;

final class ProductService
{
    public static function findByCode(array $products, string $code): object|null
    {
        foreach ($products as $product) {
            if ($product->code === $code) {
                return $product;
            }
        }
        return null;
    }
}
