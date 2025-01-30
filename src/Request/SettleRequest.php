<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Response\SettleResponse;

class SettleRequest extends ApiRequest
{
    public function __construct(
        Credential              $credential,
        private readonly string $orderReference,
        private readonly float  $amount,
        private readonly string $currency,
    ) {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency
        ]);
    }

    protected function getResponseSignatureFieldsRequired(): array
    {
        return [
            "merchantAccount",
            "orderReference",
            "transactionStatus",
            "reasonCode",
        ];
    }

    protected function getType(): string
    {
        return "SETTLE";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency
        ]);
    }

    public function send(): SettleResponse
    {
        $data = $this->sendRequest();
        return new SettleResponse($data);
    }
}