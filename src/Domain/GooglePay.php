<?php

namespace Olek\WayForPay\Domain;

use Olek\WayForPay\Contract\CardInterface;

final readonly class GooglePay implements CardInterface
{
    public function __construct(
        public int $apiVersionMinor,
        public int $apiVersion,
        public PMData $paymentMethodData,
    ) {}
}