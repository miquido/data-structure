<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar\Number;

use Webmozart\Assert\Assert;

final class NumberValue implements NumberValueInterface
{
    /**
     * @var int|float
     */
    private $number;

    public static function create($value): NumberValueInterface
    {
        return new NumberValue($value);
    }

    public function __construct($value)
    {
        if (!\is_int($value) && !\is_float($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected integer or float, got %s', \gettype($value)));
        }

        $this->number = $value;
    }

    public function round(int $precision = 0, int $mode = \PHP_ROUND_HALF_UP): NumberValueInterface
    {
        return new NumberValue(\round($this->float(), $precision, $mode));
    }

    public function map(callable $callback): NumberValueInterface
    {
        $value = $callback($this->number);
        Assert::numeric($value, \sprintf('Callback should return a number, but %s was returned', \gettype($value)));

        return new NumberValue($value);
    }

    public function int(): int
    {
        return (int) $this->number;
    }

    public function float(): float
    {
        return (float) $this->number;
    }

    /**
     * @return float|int
     */
    public function get()
    {
        return $this->number;
    }

    public function toScalar()
    {
        return $this->number;
    }
}