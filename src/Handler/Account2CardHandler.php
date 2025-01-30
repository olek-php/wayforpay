<?php


namespace Olek\WayForPay\Handler;


use Olek\WayForPay\Response\ServiceResponse;
use Olek\WayForPay\Exception\SignatureException;
use Olek\WayForPay\Helper\SignatureHelper;

readonly class Account2CardHandler extends ServiceUrlHandler
{
    protected function getFieldForSignature(ServiceResponse $response): array
    {
        $transaction = $response->getTransaction();
        return [
            $this->credential->getAccount(),
            $transaction->getOrderReference(),
            $transaction->getAmount(),
            $transaction->getCurrency(),
            $transaction->getAuthCode(),
            $transaction->getStatus()->value,
            $response->getReason()->getCode()
        ];
    }
}