<?php

namespace Olek\WayForPay\Exception;

class SignatureException extends WayForPayException
{
    public function __construct(string $expected, string $merchant)
    {
        $message = sprintf("Response signature mismatch: expected %s, got %s", $expected, $merchant);
        parent::__construct($message);
    }
}