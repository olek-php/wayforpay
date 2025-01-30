<?php

namespace Olek\WayForPay\Contract;

interface RequestInterface
{
    public function send(): ResponseInterface;
}