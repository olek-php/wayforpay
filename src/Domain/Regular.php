<?php

namespace Olek\WayForPay\Domain;

use DateTimeInterface;
use Olek\WayForPay\Enum\RegularBehavior;
use Olek\WayForPay\Enum\RegularMode;
use Olek\WayForPay\Exception\InvalidFieldException;

final readonly class Regular
{
    private const DELIMITER = ";";

    public function __construct(
        /** @var RegularMode[] */
        private array $modes,
        private ?float $amount = null,
        private ?DateTimeInterface $dateNext = null,
        private ?DateTimeInterface $dateEnd = null,
        private ?int $count = null,
        private ?bool $on = null,
        private ?RegularBehavior $behavior = null
    ) {
        if ($behavior && $behavior === RegularBehavior::PRESET) {
            if (!$modes) {
                throw new InvalidFieldException("Modes is required");
            }

            if (!$amount) {
                throw new InvalidFieldException("Amount is required");
            }

            if (!$dateNext) {
                throw new InvalidFieldException("Date next is required");
            }

            if (!$dateEnd && !$count) {
                throw new InvalidFieldException("Date end or count are required");
            }
        }
    }

    public function getModesAsString(): string
    {
        $values = [];
        foreach ($this->modes as $mode) {
            $values[] = $mode->value;
        }
        return implode(self::DELIMITER, $values);
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getDateNext(): ?DateTimeInterface
    {
        return $this->dateNext;
    }

    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function isOn(): ?bool
    {
        return $this->on;
    }

    public function getBehaviorValue(): ?string
    {
        return $this->behavior?->value;
    }
}