<?php

namespace Olek\WayForPay\Collection;

use ArrayIterator;
use Closure;
use Olek\WayForPay\Contract\CollectionInterface;
use Traversable;

use function array_key_exists;
use function array_map;
use function array_search;
use function count;

class ArrayCollection implements CollectionInterface
{
    public function __construct(private array $elements = [])
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function add(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function get(string|int $key)
    {
        return $this->elements[$key] ?? null;
    }

    public function containsKey(string|int $key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    public function remove(string|int $key): mixed
    {
        if (! isset($this->elements[$key]) && ! array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    public function set(string|int $key, mixed $value): void
    {
        $this->elements[$key] = $value;
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function values(): array
    {
        return array_values($this->elements);
    }

    public function removeElement(mixed $element): bool
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    public function map(Closure $func): CollectionInterface
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    protected function createFrom(array $elements): self
    {
        return new self($elements);
    }
}