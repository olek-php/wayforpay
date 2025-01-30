<?php

namespace Olek\WayForPay\Collection;

use InvalidArgumentException;
use Olek\WayForPay\Contract\SignatureAbleInterface;
use Olek\WayForPay\Domain\Product;

class ProductCollection extends ArrayCollection implements SignatureAbleInterface
{
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            if (!$element instanceof Product) {
                throw new \InvalidArgumentException("Expect Transaction, got " . $element::class);
            }
        }
        parent::__construct($elements);
    }

    public function add(mixed $element): void
    {
        if (!$element instanceof Product) {
            throw new InvalidArgumentException("Expect Product, got " . $element::class);
        }

        parent::add($element);
    }

    public function getConcatenatedString(string $delimiter): string
    {
        return implode($delimiter, $this->getNames()) . $delimiter .
            implode($delimiter, $this->getCounts()) . $delimiter .
            implode($delimiter, $this->getPrices());
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->map(static function (Product $product) {
            return $product->getName();
        })->values();
    }

    /**
     * @return int[]
     */
    public function getCounts(): array
    {
        return $this->map(function (Product $product) {
            return $product->getCount();
        })->values();
    }

    /**
     * @return float[]
     */
    public function getPrices(): array
    {
        return $this->map(function (Product $product) {
            return $product->getPrice();
        })->values();
    }
}