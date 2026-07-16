<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Response\Account2CardResponse;
use Olek\WayForPay\Credential\Credential;

readonly class Account2CardRequest extends ApiRequest
{
    public function __construct(
        Credential                $credential,
        private string   $orderReference,
        private string   $debetOrderRef,
        private float    $amount,
        private string   $currency,
        private string   $cardBeneficiary,
        private string   $rec2Token,
        private AuthType $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private ?string  $serviceUrl = null,
        private ?string  $recipientFirstName = null,
        private ?string  $recipientLastName = null,
        private ?string  $recipientPhone = null,
        private ?string  $recipientEmail = null
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