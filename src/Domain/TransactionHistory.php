<?php

namespace Olek\WayForPay\Domain;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Olek\WayForPay\Enum\TransactionStatus;

readonly class TransactionHistory extends TransactionBase
{
    private const REQUIRED_FIELDS = [
        "transactionType",
        "orderReference",
        "createdDate",
        "amount",
        "currency",
        "transactionStatus",
        "processingDate",
        "reasonCode",
        "reason",
    ];

    public static function fromArray(array $data): TransactionHistory
    {
        if ($fieldsMissed = array_diff(self::REQUIRED_FIELDS, array_keys($data))) {
            throw new InvalidArgumentException(
                "Transaction data have missed fields: " . implode(", ", $fieldsMissed)
            );
        }

        return new self(
            $data["transactionType"],
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
            (isset($data["settlementDate"]) ? new DateTime("@" . $data["settlementDate"]) : null),
            ($data["settlementAmount"] ?? null)
        );
    }

    public function __construct(
        private string      $type,
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
        private ?DateTimeInterface $settlementDate = null,
        private ?float $settlementAmount = null,
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

    protected function getType(): string
    {
        return $this->type;
    }

    public function getSettlementDate(): ?DateTimeInterface
    {
        return $this->settlementDate;
    }

    public function getSettlementAmount(): ?float
    {
        return $this->settlementAmount;
    }
}