<?php

namespace Olek\WayForPay\Domain;

final readonly class Delivery
{
    public function __construct(
        private ?string $nameFirst = null,
        private ?string $nameLast = null,
        private ?string $address = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $zip = null,
        private ?string $country = null,
        private ?string $email = null,
        private ?string $phone = null
    ) {
    }

    public function getNameFirst(): ?string
    {
        return $this->nameFirst;
    }

    public function getNameLast(): ?string
    {
        return $this->nameLast;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}