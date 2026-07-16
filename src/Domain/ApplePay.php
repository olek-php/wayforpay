<?php

namespace Olek\WayForPay\Domain;

use Olek\WayForPay\Contract\CardInterface;

final readonly class ApplePay implements CardInterface
{
    public function __construct(
        public string $token,
    ) {}
}