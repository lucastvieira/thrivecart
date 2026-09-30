<?php

namespace Thrivecart\Server\Action;

use Thrivecart\Server\Catalog;
use Thrivecart\Server\Response;
use Thrivecart\Server\Controller;

final class CartController extends Controller
{
    public const OFFER_BOGO_50_ON_SECOND_ITEM = 'buy_one_get_second_half_price';

    public function calculate(): void
    {
        $payload = $this->request();

        if (!$payload || !isset($payload->items) || !is_array($payload->items)) {
            Response::error('Items are required');
        }

        $catalog = new Catalog();
        $products = $catalog->products();
        $delivery = $catalog->delivery();
        $offers = $catalog->offers();

        $subtotal = $this->calculateTotal($products, $payload->items);

        $discount = $this->applyOffers($offers, $products, $payload->items);
        $subtotal -= $discount;

        $shipping = $this->calculateShipping($delivery, $subtotal);
        $total = $subtotal + $shipping;

        Response::json([
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
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

    private function applyOffers(array $offers, array $products, array $items): int
    {
        $discount = 0;

        foreach ($offers as $offer) {
            if ($offer->type !== self::OFFER_BOGO_50_ON_SECOND_ITEM) {
                continue;
            }

            $filtered = array_filter($items, function ($item) use ($offer) {
                return $item->product->code === $offer->product;
            });

            if (empty($filtered)) {
                continue;
            }

            $product = $this->findProductByCode($products, $offer->product);
            if (!$product) {
                continue;
            }

            $item = array_shift($filtered);
            $pairs = intdiv($item->quantity, 2);
            $halfPrice = ceil($product->price / 2);
            $discount += $pairs * $halfPrice;
        }

        return $discount;
    }

    private function findProductByCode(array $products, string $code): object|null
    {
        foreach ($products as $product) {
            if ($product->code === $code) {
                return $product;
            }
        }
        return null;
    }
}
