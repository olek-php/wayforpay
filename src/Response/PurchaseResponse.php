<?php

namespace Olek\WayForPay\Response;

use LogicException;
use Olek\WayForPay\Contract\ResponseInterface;
use Olek\WayForPay\Domain\Reason;

readonly class PurchaseResponse implements ResponseInterface
{
    public string $url;

    public function __construct(array $data)
    {
        $this->url = $data["url"];
    }

    public function getReason(): Reason
    {
        throw new LogicException("Not implemented");
    }
}