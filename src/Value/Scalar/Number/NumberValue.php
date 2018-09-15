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
        Assert::numeric($value);

        $this->number = $value + 0; // cast to int or float
    }

    public function map(callable $callback): NumberValueInterface
    {
        return new NumberValue($callback($this->number));
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
