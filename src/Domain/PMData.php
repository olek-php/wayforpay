<?php

namespace Olek\WayForPay\Domain;

final readonly class PMData
{
    public function __construct(
        public string $type,
        public string $description,
        public PMDataInfo $info,
        public PMDataTData $tokenizationData,
    ) {}
}