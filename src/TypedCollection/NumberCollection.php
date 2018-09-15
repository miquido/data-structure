<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

final class NumberCollection implements NumberCollectionInterface
{
    /**
     * @var float[]
     */
    private $numbers;

    public static function create(float ...$numbers): NumberCollectionInterface
    {
        return new NumberCollection(...$numbers);
    }

    public function __construct(float ...$numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @param float ...$numbers
     *
     * @return NumberCollectionInterface
     */
    public function push(float ...$numbers): NumberCollectionInterface
    {
        return new NumberCollection(...\array_merge($this->numbers, $numbers));
    }

    public function unique(): NumberCollectionInterface
    {
        return new NumberCollection(...\array_reduce(
            $this->numbers,
            function (array $carry, float $number): array {
                if (!\in_array($number, $carry, true)) {
                    $carry[] = $number;
                }

                return $carry;
            },
            []
        ));
    }

    /**
     * @param float $number
     *
     * @return bool
     */
    public function includes(float $number): bool
    {
        return \in_array($number, $this->numbers, true);
    }

    /**
     * @return float[]
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
