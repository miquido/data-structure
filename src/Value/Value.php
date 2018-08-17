<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\HashMap\HashMap;
use Miquido\DataStructure\HashMap\HashMapInterface;
use Miquido\DataStructure\Value\Collection\CollectionValue;
use Miquido\DataStructure\Value\Collection\CollectionValueInterface;
use Miquido\DataStructure\Value\Scalar\ScalarValue;
use Miquido\DataStructure\Value\Scalar\ScalarValueInterface;
use Webmozart\Assert\Assert;

final class Value implements ValueInterface
{
    private $rawValue;

    public static function create($rawValue): ValueInterface
    {
        return new Value($rawValue);
    }

    public function __construct($rawValue)
    {
        $this->rawValue = $rawValue;
    }

    public function scalar(): ScalarValueInterface
    {
        return new ScalarValue($this->rawValue);
    }

    public function collection(bool $castScalar = true): CollectionValueInterface
    {
        $value = $castScalar && \is_scalar($this->rawValue) ? [$this->rawValue] : $this->rawValue;
        $value = $value instanceof ArrayConvertibleInterface ? $value->toArray() : $value;
        Assert::isArray($value);

        return new CollectionValue($value);
    }

    public function getRawValue()
    {
        return $this->rawValue;
    }

    public function hashMap(): HashMapInterface
    {
        $value = $this->rawValue instanceof ArrayConvertibleInterface ? $this->rawValue->toArray() : $this->rawValue;
        Assert::isArray($value, \sprintf('Value type %s could not be converted to HashMap', \gettype($this->rawValue)));

        return new HashMap($value);
    }
}