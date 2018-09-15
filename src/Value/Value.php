<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\Map\Map;
use Miquido\DataStructure\Map\MapInterface;
use Miquido\DataStructure\Value\Collection\CollectionValue;
use Miquido\DataStructure\Value\Collection\CollectionValueInterface;
use Miquido\DataStructure\Value\Scalar\Number\NumberValue;
use Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface;
use Miquido\DataStructure\Value\Scalar\ScalarValue;
use Miquido\DataStructure\Value\Scalar\ScalarValueInterface;
use Miquido\DataStructure\Value\Scalar\String\StringValue;
use Miquido\DataStructure\Value\Scalar\String\StringValueInterface;
use Webmozart\Assert\Assert;

final class Value implements ValueInterface
{
    private $value;

    public static function create($rawValue): ValueInterface
    {
        return new Value($rawValue);
    }

    public function __construct($rawValue)
    {
        $this->value = $rawValue;
    }

    public function toMap(): MapInterface
    {
        return new Map($this->value);
    }

    public function toCollectionValue(bool $castScalar = true): CollectionValueInterface
    {
        $value = $castScalar && \is_scalar($this->value) ? [$this->value] : $this->value;
        $value = $value instanceof ArrayConvertibleInterface ? $value->toArray() : $value;
        Assert::isArray($value);

        return new CollectionValue($value);
    }

    public function toScalarValue(): ScalarValueInterface
    {
        return new ScalarValue($this->value);
    }

    public function toStringValue(): StringValueInterface
    {
        return new StringValue($this->value);
    }

    public function toNumberValue(): NumberValueInterface
    {
        return new NumberValue($this->value);
    }

    public function string(): string
    {
        return $this->toStringValue()->get();
    }

    public function int(): int
    {
        return $this->toNumberValue()->int();
    }

    public function float(): float
    {
        return $this->toNumberValue()->float();
    }

    public function bool(bool $parseString = true): bool
    {
        return $this->toScalarValue()->bool($parseString);
    }

    public function dateTime(): \DateTime
    {
        return $this->toScalarValue()->dateTime();
    }

    public function getRawValue()
    {
        return $this->value;
    }
}
