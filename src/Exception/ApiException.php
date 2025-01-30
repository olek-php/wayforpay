<?php

namespace Olek\WayForPay\Exception;

use Olek\WayForPay\Domain\Reason;

class ApiException extends WayForPayException
{
    public function __construct(Reason $reason)
    {
        parent::__construct($reason->getMessage(), $reason->getCode());
    }
}