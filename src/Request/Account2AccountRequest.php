<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Response\Account2AccountResponse;

readonly class Account2AccountRequest extends ApiRequest
{
    public function __construct(
        Credential       $credential,
        private string   $orderReference,
        private string   $debetOrderRef,
        private float    $amount,
        private string   $currency,
        private string   $iban,
        private string   $okpo,
        private string   $accountName,
        private AuthType $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private ?string  $description = null,
        private ?string  $serviceUrl = null,
        private ?string  $recipientLastName = null,
        private ?string  $recipientPhone = null,
        private ?string  $recipientEmail = null
    )
    {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [

            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "iban" => $this->iban,
            "okpo" => $this->okpo,
            "accountName" => $this->accountName
        ]);
    }

    protected function getResponseSignatureFieldsRequired(): array
    {
        return [
            "merchantAccount",
            "orderReference",
            "amount",
            "currency",
            "transactionStatus",
            "reasonCode"
        ];
    }

    protected function getType(): string
    {
        return "P2P_ACCOUNT";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "merchantAuthType" => $this->merchantAuthType,
            "orderReference" => $this->orderReference,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "iban" => $this->iban,
            "okpo" => $this->okpo,
            "accountName" => $this->accountName,
            "description" => $this->description,
            "serviceUrl" => $this->serviceUrl,
            "recipientLastName" => $this->recipientLastName,
            "recipientPhone" => $this->recipientPhone,
            "recipientEmail" => $this->recipientEmail,
            "debetOrderRef" => $this->debetOrderRef,
        ]);
    }

    public function send(): Account2AccountResponse
    {
        $data = $this->sendRequest();
        return new Account2AccountResponse($data);
    }
}