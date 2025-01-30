<?php

namespace Olek\WayForPay\Domain;

use DateTimeInterface;

final readonly class Avia
{
    public function __construct(
        private ?DateTimeInterface $departureDate = null,
        private ?string $locationNumber = null,
        private ?string $locationCodes = null,
        private ?string $nameFirst = null,
        private ?string $nameLast = null,
        private ?string $reservationCode = null
    ) {
    }

    public function getDepartureDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }

    public function getLocationNumber(): ?string
    {
        return $this->locationNumber;
    }

    public function getLocationCodes(): ?string
    {
        return $this->locationCodes;
    }

    public function getNameFirst(): ?string
    {
        return $this->nameFirst;
    }

    public function getNameLast(): ?string
    {
        return $this->nameLast;
    }

    public function getReservationCode(): ?string
    {
        return $this->reservationCode;
    }
}