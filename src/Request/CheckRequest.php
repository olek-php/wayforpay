<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Response\CheckResponse;

class CheckRequest extends ApiRequest
{

    public function __construct(
        Credential $credential,
        private readonly string $orderReference
    )
    {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "orderReference" => $this->orderReference,
        ]);
    }

    protected function getResponseSignatureFieldsRequired(): array
    {
        return [
            "merchantAccount",
            "orderReference",
            "amount",
            "currency",
            "authCode",
            "cardPan",
            "transactionStatus",
            "reasonCode",
        ];
    }

    protected function getType(): string
    {
        return "CHECK_STATUS";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "orderReference" => $this->orderReference
        ]);
    }

    public function send(): CheckResponse
    {
        $data = $this->sendRequest();
        return new CheckResponse($data);
    }
}