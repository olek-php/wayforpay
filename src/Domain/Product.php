<?php

namespace Olek\WayForPay\Domain;

final readonly class Product
{
    public function __construct(
        private string $name,
        private float $price,
        private int $count
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCount(): int
    {
        return $this->count;
    }


}