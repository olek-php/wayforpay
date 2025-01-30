<?php

namespace Olek\WayForPay\Response;

use Olek\WayForPay\Domain\TransactionService;

final readonly class ServiceResponse extends Response
{
    private TransactionService $transaction;


    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->transaction = TransactionService::fromArray($data);
    }

    public function getTransaction(): TransactionService
    {
        return $this->transaction;
    }
}