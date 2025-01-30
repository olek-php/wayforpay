<?php

namespace Olek\WayForPay\Response;

final readonly class SettleResponse extends Response
{
    private string $orderReference;
    private string $transactionStatus;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->orderReference = $data["orderReference"];
        $this->transactionStatus = $data["transactionStatus"];
    }

    public function getOrderReference(): string
    {
        return $this->orderReference;
    }

    public function getTransactionStatus(): string
    {
        return $this->transactionStatus;
    }
}