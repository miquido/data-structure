<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Collection;

use Miquido\DataStructure\TypedCollection\IntegerCollection;
use Miquido\DataStructure\TypedCollection\IntegerCollectionInterface;
use Miquido\DataStructure\TypedCollection\NumberCollection;
use Miquido\DataStructure\TypedCollection\NumberCollectionInterface;
use Miquido\DataStructure\TypedCollection\ObjectCollection;
use Miquido\DataStructure\TypedCollection\ObjectCollectionInterface;
use Miquido\DataStructure\TypedCollection\StringCollection;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;
use Webmozart\Assert\Assert;

final class CollectionValue implements CollectionValueInterface
{
    /**
     * @var array
     */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function strings(): StringCollectionInterface
    {
        Assert::allString($this->values);

        return new StringCollection(...$this->values);
    }

    public function numbers(): NumberCollectionInterface
    {
        Assert::allNumeric($this->values);

        return new NumberCollection(...$this->values);
    }

    public function integers(): IntegerCollectionInterface
    {
        Assert::allIntegerish($this->values);

        return new IntegerCollection(...\array_map(function ($value): int {
            return (int) $value;
        }, $this->values));
    }

    public function objects(): ObjectCollectionInterface
    {
        Assert::allObject($this->values);

        return new ObjectCollection(...$this->values);
    }

    public function get(): array
    {
        return $this->values;
    }

    public function keys(): array
    {
        return \array_keys($this->values);
    }

    public function values(): array
    {
        return \array_values($this->values);
    }

    public function count(): int
    {
        return \count($this->values);
    }

    public function toArray(): array
    {
        return $this->values;
    }
}