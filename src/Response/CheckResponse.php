<?php

namespace Olek\WayForPay\Response;

use Olek\WayForPay\Domain\Order;

final readonly class CheckResponse extends Response
{
    private Order $order;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->order = Order::fromArray($data);
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}