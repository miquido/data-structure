<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

final class NumberCollection extends BaseNumberCollection implements NumberCollectionInterface
{
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
        return new NumberCollection(...$this->getUniqueNumbers());
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
}
