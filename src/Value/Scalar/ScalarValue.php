<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollection;
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
        $value = $value instanceof ScalarConvertibleInterface ? $value->toScalar() : $value;

        Assert::scalar($value);
        $this->value = $value;
    }

    public function map(callable $callback): ScalarValueInterface
    {
        $value = $callback($this->value);
        Assert::scalar($value);

        return new ScalarValue($value);
    }

    public function string(): StringValueInterface
    {
        Assert::string($this->value);

        return new StringValue($this->value);
    }

    public function number(): NumberValueInterface
    {
        $value = \is_int($this->value) ? $this->value : (float) $this->value;
        Assert::numeric($value);

        return new NumberValue($value);
    }

    public function bool(bool $parseString = true): bool
    {
        if ($parseString && \is_string($this->value)) {
            if (\in_array(\mb_strtolower($this->value), ['false', 'null', '0', 'no'], true)) {
                return false;
            }

            return (bool) $this->value;
        }

        Assert::boolean($this->value);

        return (bool) $this->value;
    }

    public function date(): \DateTime
    {
        if ($this->value instanceof \DateTime) {
            return $this->value;
        }

        if (\is_string($this->value)) {
            return new \DateTime($this->value);
        }

        if (\is_int($this->value)) {
            return new \DateTime(\date('Y-m-d H:i:s', $this->value));
        }

        throw new \InvalidArgumentException(\sprintf('Could not transform %s to DateTime', \gettype($this->value)));
    }

    public function cast(string $type): ScalarValueInterface
    {
        $acceptedTypes = new StringCollection('integer', 'int', 'float', 'string', 'boolean', 'bool');
        $type = StringValue::create($type)->toLower()->trim()->get();

        if (!$acceptedTypes->includes($type)) {
            throw new \InvalidArgumentException(\sprintf('Invalid type "%s" (accepted values: %s)', $type, $acceptedTypes->join(', ')));
        }

        $value = $this->value;
        $castResult = \settype($value, $type);
        if (!$castResult) {
            throw new \RuntimeException(\sprintf(
                'Cannot cast variable %s (type %s) to %s', $this->value, \gettype($this->value), $type
            ));
        }

        return new ScalarValue($value);
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