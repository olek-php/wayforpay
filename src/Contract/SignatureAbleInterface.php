<?php

namespace Olek\WayForPay\Contract;

interface SignatureAbleInterface
{
    public function getConcatenatedString(string $delimiter): string;
}