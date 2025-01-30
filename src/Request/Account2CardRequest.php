<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Response\Account2CardResponse;
use Olek\WayForPay\Credential\Credential;

class Account2CardRequest extends ApiRequest
{
    public function __construct(
        Credential                $credential,
        private readonly string   $orderReference,
        private readonly string   $debetOrderRef,
        private readonly float    $amount,
        private readonly string   $currency,
        private readonly string   $cardBeneficiary,
        private readonly string   $rec2Token,
        private readonly AuthType $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private readonly ?string  $serviceUrl = null,
        private readonly ?string  $recipientFirstName = null,
        private readonly ?string  $recipientLastName = null,
        private readonly ?string  $recipientPhone = null,
        private readonly ?string  $recipientEmail = null
    ) {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "cardBeneficiary" => $this->cardBeneficiary,
            "rec2Token" => $this->rec2Token
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
            "transactionStatus",
            "reasonCode"
        ];
    }

    protected function getType(): string
    {
        return "P2P_CREDIT";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "merchantAuthType" => $this->merchantAuthType,
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "cardBeneficiary" => $this->cardBeneficiary,
            "rec2Token" => $this->rec2Token,
            "serviceUrl" => $this->serviceUrl,
            "recipientFirstName" => $this->recipientFirstName,
            "recipientLastName" => $this->recipientLastName,
            "recipientPhone" => $this->recipientPhone,
            "recipientEmail" => $this->recipientEmail,
            "debetOrderRef" => $this->debetOrderRef
        ]);
    }

    public function send(): Account2CardResponse
    {
        $data = $this->sendRequest();
        return new Account2CardResponse($data);
    }
}