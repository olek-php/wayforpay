<?php
namespace Olek\WayForPay\Domain;

use Olek\WayForPay\Contract\CardInterface;

final readonly class CardToken implements CardInterface
{

    public function __construct(private string $token)
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}