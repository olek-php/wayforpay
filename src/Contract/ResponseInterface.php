<?php

namespace Olek\WayForPay\Contract;

use Olek\WayForPay\Domain\Reason;

interface ResponseInterface
{
    public function getReason(): Reason;
}