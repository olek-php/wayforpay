<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Response\Account2AccountResponse;

class Account2AccountRequest extends ApiRequest
{
    public function __construct(
        Credential                $credential,
        private readonly string   $orderReference,
        private readonly string   $debetOrderRef,
        private readonly float    $amount,
        private readonly string   $currency,
        private readonly string   $iban,
        private readonly string   $okpo,
        private readonly string   $accountName,
        private readonly AuthType $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private readonly ?string  $description = null,
        private readonly ?string  $serviceUrl = null,
        private readonly ?string  $recipientLastName = null,
        private readonly ?string  $recipientPhone = null,
        private readonly ?string  $recipientEmail = null
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