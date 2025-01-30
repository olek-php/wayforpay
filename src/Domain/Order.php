<?php

namespace Olek\WayForPay\Domain;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Olek\WayForPay\Enum\TransactionStatus;

readonly class Order extends TransactionBase
{
    private const REQUIRED_FIELDS =  [
        "authCode",
        "orderReference",
        "createdDate",
        "amount",
        "currency",
        "transactionStatus",
        "processingDate",
        "reasonCode",
        "reason",
    ];

    public static function fromArray(array $data): Order
    {
        if ($fieldsMissed = array_diff(self::REQUIRED_FIELDS, array_keys($data))) {
            throw new InvalidArgumentException(
                "Transaction data have missed fields: " . implode(", ", $fieldsMissed)
            );
        }

        return new self(
            $data["orderReference"],
            new DateTime("@" . $data["createdDate"]),
            $data["amount"],
            $data["currency"],
            TransactionStatus::from($data["transactionStatus"]),
            new DateTime("@" . $data["processingDate"]),
            new Reason($data["reasonCode"], $data["reason"]),
            ($data["cardPan"] ?? null),
            ($data["cardType"] ?? null),
            ($data["issuerBankCountry"] ?? null),
            ($data["issuerBankName"] ?? null),
            ($data["fee"] ?? null),
            ($data["baseAmount"] ?? null),
            ($data["baseCurrency"] ?? null),
            ($data["authCode"] ?? null),
            (isset($data["settlementDate"]) ? new DateTime("@" . $data["settlementDate"]) : null),
            ($data["settlementAmount"] ?? null),
            ($data["refundAmount"] ?? null)
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
        ?string             $cardPan = null,
        ?string             $cardType = null,
        ?string             $issuerBankCountry = null,
        ?string             $issuerBankName = null,
        ?float              $fee = null,
        ?float              $baseAmount = null,
        ?string             $baseCurrency = null,
        private ?string $authCode = null,
        private ?DateTimeInterface $settlementDate = null,
        private ?float $settlementAmount = null,
        private ?float $refundAmount = null
    ) {
        parent::__construct(
            $orderReference,
            $createdDate,
            $amount,
            $currency,
            $status,
            $processingDate,
            $reason,
            null,
            null,
            null,
            $cardPan,
            $cardType,
            $issuerBankCountry,
            $issuerBankName,
            $fee,
            $baseAmount,
            $baseCurrency
        );
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function getSettlementDate(): ?DateTimeInterface
    {
        return $this->settlementDate;
    }

    public function getSettlementAmount(): ?float
    {
        return $this->settlementAmount;
    }

    public function getRefundAmount(): ?float
    {
        return $this->refundAmount;
    }
}
