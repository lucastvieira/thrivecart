<?php

namespace Thrivecart\Server\Model;

class DiscountOfferProduct
{
    public function __construct(
        public string $code,
        public int $amount,
    ) {
        $this->code = $code;
        $this->amount = $amount;
    }
}
