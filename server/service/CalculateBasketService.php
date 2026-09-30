<?php

namespace Thrivecart\Server\Service;

final class CalculateBasketService
{
    public static function total(array $products, array $payload): int
    {
        $total = 0;

        foreach ($products as $product) {
            $payloadItems = array_filter($payload, function ($item) use ($product) {
                return $item->product->code === $product->code;
            });

            if (empty($payloadItems)) {
                continue;
            }

            $payloadItem = array_shift($payloadItems);
            $total += $payloadItem->quantity * $product->price;
        }

        return $total;
    }
}
