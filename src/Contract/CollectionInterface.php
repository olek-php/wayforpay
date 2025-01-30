<?php

namespace Olek\WayForPay\Contract;

use ArrayAccess;
use Closure;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends ArrayAccess, Countable, IteratorAggregate
{
    public function add(mixed $element): void;
    public function clear(): void;
    public function remove(string|int $key): mixed;
    public function removeElement(mixed $element): bool;
    public function set(string|int $key, mixed $value): void;
    public function map(Closure $func): CollectionInterface;
}