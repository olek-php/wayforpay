<?php

namespace Olek\WayForPay\Domain;

use InvalidArgumentException;
use Olek\WayForPay\Contract\CardInterface;

final readonly class Card implements CardInterface
{
    public function __construct(
        private string $card,
        private int $month,
        private int $year,
        private int $cvv,
        private string $holder
    ) {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException("Invalid month");
        }

        if ($year < (int)date("Y")) {
            throw new InvalidArgumentException("Invalid year");
        }
    }

    public function getCard(): string
    {
        return $this->card;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getCvv(): int
    {
        return $this->cvv;
    }

    public function getHolder(): string
    {
        return $this->holder;
    }
}