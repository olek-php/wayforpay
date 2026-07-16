<?php

namespace Olek\WayForPay\Domain;

final readonly class PMDataTData
{
    public function __construct(
        public string $type,
        public string $token,
    ) {}
}