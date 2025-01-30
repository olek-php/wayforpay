<?php

namespace Olek\WayForPay\Domain;

use DateTime;
use DateTimeInterface;
use Olek\WayForPay\Enum\TransactionStatus;

readonly class Transaction extends TransactionBase
{
    private const REQUIRED_FIELDS = [
        "merchantTransactionType",
        "authCode",
        "authTicket",
        "recToken",
        "d3AcsUrl",
        "d3Md",
        "d3Pareq",
        "returnUrl",
        "orderReference",
        "createdDate",
        "amount",
        "currency",
        "transactionStatus",
        "processingDate",
        "reasonCode",
        "reason",
    ];

    public static function fromArray(array $data): Transaction
    {
        if ($fieldsMissed = array_diff(self::REQUIRED_FIELDS, array_keys($data))) {
            throw new \InvalidArgumentException(
                "Transaction data have missed fields: " . implode(", ", $fieldsMissed)
            );
        }

        return new self(
            $data["merchantTransactionType"],
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
            ($data["authCode"] ?? null),
            ($data["authTicket"] ?? null),
            ($data["recToken"] ? new CardToken($data["recToken"]) : null),
            ($data["d3AcsUrl"] ?? null),
            ($data["d3Md"] ?? null),
            ($data["d3Pareq"] ?? null),
            ($data["returnUrl"] ?? null),
        );
    }

    public function __construct(
        private string      $merchantTransactionType,
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
        private ?string $authCode = null,
        private ?string $authTicket = null,
        private ?CardToken $recToken = null,
        private ?string $d3AcsUrl = null,
        private ?string $d3Md = null,
        private ?string $d3Pareq = null,
        private ?string $returnUrl = null
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

    public function getMerchantTransactionType(): string
    {
        return $this->merchantTransactionType;
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function getAuthTicket(): ?string
    {
        return $this->authTicket;
    }

    public function getRecToken(): ?CardToken
    {
        return $this->recToken;
    }

    public function getD3AcsUrl(): ?string
    {
        return $this->d3AcsUrl;
    }

    public function getD3Md(): ?string
    {
        return $this->d3Md;
    }

    public function getD3Pareq(): ?string
    {
        return $this->d3Pareq;
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }
}