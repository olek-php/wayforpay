<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Response\RefundResponse;

class RefundRequest extends ApiRequest
{
    public function __construct(
        Credential $credential,
        private readonly string $orderReference,
        private readonly string $amount,
        private readonly string $currency,
        private readonly string $comment,
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
        return "REFUND";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "comment" => $this->comment
        ]);
    }

    public function send(): RefundResponse
    {
        $data = $this->sendRequest();
        return new RefundResponse($data);
    }
}