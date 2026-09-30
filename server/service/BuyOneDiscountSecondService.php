<?php

namespace Thrivecart\Server\Service;

use Thrivecart\Server\Model\DiscountOffer;
use Thrivecart\Server\Model\DiscountOfferProduct;

final class BuyOneDiscountSecondService
{
    public const OFFER_BOGO_50_ON_SECOND_ITEM = 'buy_one_get_second_half_price';

    public static function apply(array $offers, array $products, array $items): DiscountOffer
    {
        $productDiscounts = [];
        $totalDiscount = 0;

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

            $product = ProductService::findByCode($products, $offer->product);
            if (!$product) {
                continue;
            }

            $item = array_shift($filtered);
            $pairs = intdiv($item->quantity, 2);
            $halfPrice = ceil($product->price / 2);
            $discount = $pairs * $halfPrice;
            $productDiscounts[] = new DiscountOfferProduct($product->code, $discount);
            $totalDiscount += $discount;
        }

        return new DiscountOffer($productDiscounts, $totalDiscount);
    }
}
