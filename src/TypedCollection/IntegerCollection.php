<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

final class IntegerCollection extends BaseNumberCollection implements IntegerCollectionInterface
{
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
        return new IntegerCollection(...$this->getUniqueNumbers());
    }

    public function includes(int $number): bool
    {
        return \in_array($number, $this->numbers, true);
    }
}
