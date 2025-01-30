<?php

namespace Olek\WayForPay\Collection;

use InvalidArgumentException;
use Olek\WayForPay\Domain\TransactionHistory;

class TransactionHistoryCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            if (!$element instanceof TransactionHistory) {
                throw new InvalidArgumentException("Expect Transaction, got " . $element::class);
            }
        }
        parent::__construct($elements);
    }

    public function add($element): void
    {
        if (!$element instanceof TransactionHistory) {
            throw new InvalidArgumentException("Expect Transaction, got " . $element::class);
        }

        parent::add($element);
    }
}