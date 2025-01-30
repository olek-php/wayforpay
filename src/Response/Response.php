<?php

namespace Olek\WayForPay\Response;

use Olek\WayForPay\Contract\ResponseInterface;
use Olek\WayForPay\Domain\Reason;
use Olek\WayForPay\Exception\InvalidFieldException;
use Olek\WayForPay\Exception\WayForPayException;

readonly class Response implements ResponseInterface
{
    private Reason $reason;

    /**
     * @throws WayForPayException
     */
    public function __construct(array $data)
    {
        if (!isset($data["reason"])) {
            throw new InvalidFieldException("Field `reason` required");
        }

        if (!isset($data["reasonCode"])) {
            throw new InvalidFieldException("Field `reason` required");
        }

        $this->reason = new Reason($data["reasonCode"], $data["reason"]);

    }

    public function getReason(): Reason
    {
        return $this->reason;
    }
}