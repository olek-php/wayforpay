<?php

namespace Olek\WayForPay\Response;

use InvalidArgumentException;
use Olek\WayForPay\Collection\TransactionHistoryCollection;
use Olek\WayForPay\Domain\Transaction;
use Olek\WayForPay\Domain\TransactionHistory;

final readonly class TransactionListResponse extends Response
{
    private TransactionHistoryCollection $transactionList;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if (!isset($data["transactionList"])) {
            throw new InvalidArgumentException("Field `reason` required");
        }

        $this->transactionList = new TransactionHistoryCollection();

        foreach ($data["transactionList"] as $transaction) {
            $this->transactionList->add(TransactionHistory::fromArray($transaction));
        }
    }

    /**
     * @return TransactionHistoryCollection<int, Transaction>
     */
    public function getTransactionList(): TransactionHistoryCollection
    {
        return $this->transactionList;
    }
}