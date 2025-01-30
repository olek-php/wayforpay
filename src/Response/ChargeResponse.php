<?php


namespace Olek\WayForPay\Response;

use Olek\WayForPay\Domain\Transaction;

final readonly class ChargeResponse extends Response
{
    private Transaction $transaction;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->transaction = Transaction::fromArray($data);
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}