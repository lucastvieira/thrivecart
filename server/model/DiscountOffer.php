<?php

namespace Thrivecart\Server\Model;

class DiscountOffer
{
    public function __construct(
        /** @var array<DiscountOfferProduct> $products */
        public array $products,
        public int $total,
    ) {
        $this->products = $products;
        $this->total = $total;
    }
}
