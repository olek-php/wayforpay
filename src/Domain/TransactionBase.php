<?php

namespace Olek\WayForPay\Domain;

use DateTimeInterface;
use Olek\WayForPay\Enum\TransactionStatus;

readonly class TransactionBase
{
    public function __construct(
        private string              $orderReference,
        private DateTimeInterface   $createdDate,
        private float               $amount,
        private string              $currency,
        private TransactionStatus   $status,
        private DateTimeInterface   $processingDate,
        private Reason              $reason,
        private ?string             $email = null,
        private ?string             $phone = null,
        private ?string             $paymentSystem = null,
        private ?string             $cardPan = null,
        private ?string             $cardType = null,
        private ?string             $issuerBankCountry = null,
        private ?string             $issuerBankName = null,
        private ?float              $fee = null,
        private ?float              $baseAmount = null,
        private ?string             $baseCurrency = null,
    ) {
    }

    public function getOrderReference(): string
    {
        return $this->orderReference;
    }

    public function getCreatedDate(): DateTimeInterface
    {
        return $this->createdDate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getStatus(): TransactionStatus
    {
        return $this->status;
    }

    public function getProcessingDate(): DateTimeInterface
    {
        return $this->processingDate;
    }

    public function getReason(): Reason
    {
        return $this->reason;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getPaymentSystem(): ?string
    {
        return $this->paymentSystem;
    }

    public function getCardPan(): ?string
    {
        return $this->cardPan;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function getIssuerBankCountry(): ?string
    {
        return $this->issuerBankCountry;
    }

    public function getIssuerBankName(): ?string
    {
        return $this->issuerBankName;
    }

    public function getFee(): float
    {
        return $this->fee;
    }

    public function getBaseAmount(): ?float
    {
        return $this->baseAmount;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->baseCurrency;
    }

    public function isStatusCreated(): bool
    {
        return $this->status === TransactionStatus::CREATED;
    }

    public function isStatusInProcessing(): bool
    {
        return $this->status === TransactionStatus::IN_PROCESSING;
    }

    public function isStatusWaitAuthComplete(): bool
    {
        return $this->status === TransactionStatus::WAIT_AUTH_COMPLETE;
    }

    public function isStatusApproved(): bool
    {
        return $this->status === TransactionStatus::APPROVED;
    }

    public function isStatusPending(): bool
    {
        return $this->status === TransactionStatus::PENDING;
    }

    public function isStatusExpired(): bool
    {
        return $this->status === TransactionStatus::EXPIRED;
    }

    public function isStatusRefunded(): bool
    {
        return $this->status === TransactionStatus::REFUNDED;
    }

    public function isStatusVoided(): bool
    {
        return $this->status === TransactionStatus::VOIDED;
    }

    public function isStatusDeclined(): bool
    {
        return $this->status === TransactionStatus::DECLINED;
    }

    public function isStatusRefundInProcessing(): bool
    {
        return $this->status === TransactionStatus::REFUND_IN_PROCESSING;
    }
}