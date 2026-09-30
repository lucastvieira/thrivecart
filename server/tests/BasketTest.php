<?php

namespace Thrivecart\Server\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Thrivecart\Server\Catalog;
use Thrivecart\Server\Service\BuyOneDiscountSecondService;
use Thrivecart\Server\Service\CalculateBasketService;
use Thrivecart\Server\Service\ShippingService;

final class BasketTest extends TestCase
{
    #[DataProvider('baskets')]
    public function testQuote(array $items, array $expected): void
    {
        $this->assertSame($expected, $this->quote($items));
    }

    public static function baskets(): array
    {
        return [
            'blue and green' => [
                [self::addItem('B01', 1), self::addItem('G01', 1)],
                ['subtotal' => 3290, 'discount' => 0, 'shipping' => 495, 'total' => 3785],
            ],
            'two red' => [
                [self::addItem('R01', 2)],
                ['subtotal' => 4942, 'discount' => 1648, 'shipping' => 495, 'total' => 5437],
            ],
            'red and green' => [
                [self::addItem('R01', 1), self::addItem('G01', 1)],
                ['subtotal' => 5790, 'discount' => 0, 'shipping' => 295, 'total' => 6085],
            ],
            'two blue and three red' => [
                [self::addItem('B01', 2), self::addItem('R01', 3)],
                ['subtotal' => 9827, 'discount' => 1648, 'shipping' => 0, 'total' => 9827],
            ],
            'one red, no pair' => [
                [self::addItem('R01', 1)],
                ['subtotal' => 3295, 'discount' => 0, 'shipping' => 495, 'total' => 3790],
            ],
            'three red, one discounted pair drops into the lower delivery band' => [
                [self::addItem('R01', 3)],
                ['subtotal' => 8237, 'discount' => 1648, 'shipping' => 295, 'total' => 8532],
            ],
        ];
    }

    public function testDeliveryBoundaries(): void
    {
        $delivery = (new Catalog())->delivery();

        $this->assertSame(495, ShippingService::calculate($delivery, 4999));
        $this->assertSame(295, ShippingService::calculate($delivery, 5000));
        $this->assertSame(295, ShippingService::calculate($delivery, 8999));
        $this->assertSame(0, ShippingService::calculate($delivery, 9000));
    }

    private function quote(array $items): array
    {
        $catalog = new Catalog();
        $products = $catalog->products();
        $subtotal = CalculateBasketService::total($products, $items);
        $discount = BuyOneDiscountSecondService::apply($catalog->offers(), $products, $items);
        $subtotal -= $discount->total;
        $shipping = ShippingService::calculate($catalog->delivery(), $subtotal);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount->total,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    private static function addItem(string $code, int $quantity): object
    {
        return (object) [
            'product' => (object) ['code' => $code],
            'quantity' => $quantity,
        ];
    }
}
