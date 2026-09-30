<?php

namespace Thrivecart\Server\Action;

use Thrivecart\Server\Catalog;
use Thrivecart\Server\Response;
use Thrivecart\Server\Controller;
use Thrivecart\Server\Service\BuyOneDiscountSecondService;
use Thrivecart\Server\Service\CalculateBasketService;
use Thrivecart\Server\Service\ShippingService;

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

        $subtotal = CalculateBasketService::total($products, $payload->items);

        $discount = BuyOneDiscountSecondService::apply($offers, $products, $payload->items);
        $subtotal -= $discount->total;

        $shipping = ShippingService::calculate($delivery, $subtotal);
        $total = $subtotal + $shipping;

        Response::json([
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'discounts' => $discount->products,
        ]);
    }
}
