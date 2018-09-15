<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

abstract class BaseNumberCollection implements \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    protected $numbers = [];

    protected function getUniqueNumbers(): array
    {
        return \array_reduce(
            $this->numbers,
            function (array $carry, $number): array {
                if (!\in_array($number, $carry, true)) {
                    $carry[] = $number;
                }

                return $carry;
            },
            []
        );
    }

    /**
     * @return number[]
     */
    public function toArray(): array
    {
        return $this->numbers;
    }

    public function values(): array
    {
        return $this->numbers;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->numbers);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->numbers);
    }
}
