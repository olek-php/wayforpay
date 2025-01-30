<?php

namespace Olek\WayForPay\Domain;

use DateTime;
use DateTimeInterface;
use Olek\WayForPay\Enum\TransactionStatus;

readonly class TransactionService extends TransactionBase
{

    public static function fromArray(array $data): TransactionService
    {
        $default = array(
            "merchantAccount" => "",
            "orderReference" => "",
            "merchantSignature" => "",
            "amount" => 0,
            "currency" => "",
            "authCode" => 0,
            "email" => "",
            "phone" => "",
            "createdDate" => 0,
            "processingDate" => 0,
            "cardPan" => "",
            "cardType" => "",
            "issuerBankCountry" => "",
            "issuerBankName" => "",
            "recToken" => "",
            "transactionStatus" => "",
            "reason" => "",
            "reasonCode" => 0,
            "fee" => 0,
            "paymentSystem" => "",
            "repayUrl" => "",
        );

        $data = array_merge($default, $data);

        return new self(
            $data["orderReference"],
            new DateTime("@" . $data["createdDate"]),
            $data["amount"],
            $data["currency"],
            TransactionStatus::from($data["transactionStatus"]),
            new DateTime("@" . $data["processingDate"]),
            new Reason($data["reasonCode"], $data["reason"]),
            ($data["email"] ?? null),
            ($data["phone"] ?? null),
            ($data["paymentSystem"] ?? null),
            ($data["cardPan"] ?? null),
            ($data["cardType"] ?? null),
            ($data["issuerBankCountry"] ?? null),
            ($data["issuerBankName"] ?? null),
            ($data["fee"] ?? null),
            ($data["baseAmount"] ?? null),
            ($data["baseCurrency"] ?? null),
            ($data["merchantAccount"] ?? null),
            ($data["recToken"] ? new CardToken($data["recToken"]) : null),
            ($data["authCode"] ?? null),
            ($data["repayUrl"] ?? null),
        );
    }

    public function __construct(
        string              $orderReference,
        DateTimeInterface   $createdDate,
        float               $amount,
        string              $currency,
        TransactionStatus   $status,
        DateTimeInterface   $processingDate,
        Reason              $reason,
        ?string             $email = null,
        ?string             $phone = null,
        ?string             $paymentSystem = null,
        ?string             $cardPan = null,
        ?string             $cardType = null,
        ?string             $issuerBankCountry = null,
        ?string             $issuerBankName = null,
        ?float              $fee = null,
        ?float              $baseAmount = null,
        ?string             $baseCurrency = null,
        private ?string $merchantAccount = null,
        private ?CardToken $recToken = null,
        private ?string $authCode = null,
        private ?string $repayUrl = null,
    ) {
        parent::__construct(
            $orderReference,
            $createdDate,
            $amount,
            $currency,
            $status,
            $processingDate,
            $reason,
            $email,
            $phone,
            $paymentSystem,
            $cardPan,
            $cardType,
            $issuerBankCountry,
            $issuerBankName,
            $fee,
            $baseAmount,
            $baseCurrency
        );
    }

    public function getMerchantAccount(): ?string
    {
        return $this->merchantAccount;
    }

    public function getRecToken(): ?CardToken
    {
        return $this->recToken;
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function getRepayUrl(): ?string
    {
        return $this->repayUrl;
    }
}
