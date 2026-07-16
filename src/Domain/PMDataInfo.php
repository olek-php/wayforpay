<?php

namespace Olek\WayForPay\Domain;

final readonly class PMDataInfo
{
    public function __construct(
        public string $cardNetwork,
        public string $cardDetails,
    ) {}
}