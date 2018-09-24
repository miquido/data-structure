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
use Miquido\DataStructure\ArrayConverter;
use Miquido\DataStructure\Value\Scalar\Number\NumberValue;
use Miquido\DataStructure\Value\Scalar\String\StringValue;
use Webmozart\Assert\Assert;

final class CollectionValue implements CollectionValueInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function strings(): StringCollectionInterface
    {
        return new StringCollection(...\array_map(
            function ($value): string {
                return StringValue::create($value)->get();
            },
            $this->values()
        ));
    }

    public function numbers(): NumberCollectionInterface
    {
        return new NumberCollection(...\array_map(
            function ($value): float {
                return NumberValue::create($value)->float();
            },
            $this->values()
        ));
    }

    public function integers(): IntegerCollectionInterface
    {
        return new IntegerCollection(...\array_map(
            function ($value): int {
                return NumberValue::create($value)->int();
            },
            $this->values()
        ));
    }

    public function objects(): ObjectCollectionInterface
    {
        $values = $this->values();
        Assert::allObject($values);

        return new ObjectCollection(...$values);
    }

    public function get(): array
    {
        return $this->data;
    }

    public function keys(): array
    {
        return \array_keys($this->data);
    }

    public function values(): array
    {
        return \array_values($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function toArray(): array
    {
        return ArrayConverter::toArray($this->data);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }
}
