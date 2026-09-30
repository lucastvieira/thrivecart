<?php

namespace Thrivecart\Server\Action;

use Thrivecart\Server\Catalog;
use Thrivecart\Server\Response;
use Thrivecart\Server\Controller;

final class CartController extends Controller
{
    public function calculate(): void
    {
        $payload = $this->request();

        if (!$payload || !isset($payload->items) || !is_array($payload->items)) {
            Response::error('Items are required');
        }

        $catalog = new Catalog();
        $products = $catalog->products();
        $delivery = $catalog->delivery();

        $total = $this->calculateTotal($products, $payload->items);
        $shipping = $this->calculateShipping($delivery, $total);

        Response::json([
            'subtotal' => $total,
            'shipping' => $shipping,
            'total' => $total + $shipping,
        ]);
    }

    private function calculateTotal(array $products, array $payload): int
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

    private function calculateShipping(array $delivery, int $total): int
    {
        foreach ($delivery as $item) {
            if ($total < $item->under) {
                return $item->cost;
            }
        }

        return 0;
    }
}
