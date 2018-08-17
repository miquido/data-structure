<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

final class IntegerCollection implements IntegerCollectionInterface
{
    /**
     * @var int[]
     */
    private $numbers;

    public static function create(int ...$numbers): IntegerCollectionInterface
    {
        return new IntegerCollection(...$numbers);
    }

    public function __construct(int ...$numbers)
    {
        $this->numbers = $numbers;
    }

    public function push(int ...$numbers): IntegerCollectionInterface
    {
        return new IntegerCollection(...\array_merge($this->numbers, $numbers));
    }

    public function unique(): IntegerCollectionInterface
    {
        return new IntegerCollection(...\array_reduce(
            $this->numbers,
            function (array $carry, int $number): array {
                if (!\in_array($number, $carry, true)) {
                    $carry[] = $number;
                }

                return $carry;
            },
            []
        ));
    }

    public function includes(int $number): bool
    {
        return \in_array($number, $this->numbers, true);
    }

    /**
     * @return int[]
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