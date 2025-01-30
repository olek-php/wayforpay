<?php

namespace Olek\WayForPay\Contract;

interface CredentialInterface
{
    public function getAccount(): string;
    public function getSecret(): string;
}