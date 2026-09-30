<?php

namespace Thrivecart\Server\Service;

final class ShippingService
{
    public static function calculate(array $delivery, int $total): int
    {
        foreach ($delivery as $item) {
            if ($total < $item->under) {
                return $item->cost;
            }
        }

        return 0;
    }
}
