<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\Value\Scalar\Number\NumberValue;
use Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface;
use Miquido\DataStructure\Value\Scalar\String\StringValue;
use Miquido\DataStructure\Value\Scalar\String\StringValueInterface;
use Webmozart\Assert\Assert;

final class ScalarValue implements ScalarValueInterface
{
    /**
     * @var mixed
     */
    private $value;

    public static function create($value): ScalarValueInterface
    {
        return new ScalarValue($value);
    }

    public function __construct($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format(\DATE_ATOM);
        }
        $value = $value instanceof ScalarConvertibleInterface ? $value->toScalar() : $value;

        Assert::scalar($value);
        $this->value = $value;
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
        if ($parseString && \is_string($this->value) && \in_array(\mb_strtolower($this->value), ['false', 'null', '0', 'no'], true)) {
            return false;
        }

        return (bool) $this->value;
    }

    public function dateTime(): \DateTime
    {
        if (\is_string($this->value)) {
            return new \DateTime($this->value);
        }

        if (\is_int($this->value)) {
            return new \DateTime(\date('Y-m-d H:i:s', $this->value));
        }

        throw new \InvalidArgumentException(\sprintf('Could not transform %s to DateTime', \gettype($this->value)));
    }

    public function toStringValue(): StringValueInterface
    {
        return new StringValue($this->value);
    }

    public function toNumberValue(): NumberValueInterface
    {
        return new NumberValue($this->value);
    }

    public function getRawValue()
    {
        return $this->value;
    }

    public function toScalar()
    {
        return $this->value;
    }
}