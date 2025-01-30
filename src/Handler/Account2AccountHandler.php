<?php

namespace Olek\WayForPay\Handler;

use Olek\WayForPay\Response\ServiceResponse;

readonly class Account2AccountHandler extends ServiceUrlHandler
{
    protected function getFieldForSignature(ServiceResponse $response): array
    {
        $transaction = $response->getTransaction();
        return [
            $this->credential->getAccount(),
            $transaction->getOrderReference(),
            $transaction->getAmount(),
            $transaction->getCurrency(),
            $transaction->getStatus()->value,
            $response->getReason()->getCode()
        ];
    }
}