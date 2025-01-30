<?php

namespace Olek\WayForPay\Domain;


use Olek\WayForPay\Enum\PaymentSystem;

readonly class PaymentSystems
{
    private const DELIMITER = ";";

    public function __construct(
        /** @var PaymentSystem[] */
        private array $list = [],
        private ?PaymentSystem $default = null
    ) {
    }

    public function getDefaultValue(): string
    {
        return $this->default->value;
    }

    public function getListAsString(): string
    {
        $values = [];
        foreach ($this->list as $item) {
            $values[] = $item->value;
        }
        return implode(self::DELIMITER, $values);
    }
}