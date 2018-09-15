<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar\String;

use Miquido\DataStructure\TypedCollection\StringCollection;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;
use Webmozart\Assert\Assert;

final class StringValue implements StringValueInterface
{
    /**
     * @var string
     */
    private $value;

    public static function create($value): StringValueInterface
    {
        return new StringValue($value);
    }

    public function __construct($value)
    {
        if ((\is_scalar($value) && !\is_string($value)) || (\is_object($value) && \method_exists($value, '__toString'))) {
            $value = (string) $value;
        }

        Assert::string($value);

        $this->value = $value;
    }

    public function trim(string $charList = null): StringValueInterface
    {
        $charList = \is_string($charList) ? $charList : " \t\n\r\0\x0B";

        return new StringValue(\trim($this->value, $charList));
    }

    public function toLower(): StringValueInterface
    {
        return $this->map('mb_strtolower');
    }

    public function toUpper(): StringValueInterface
    {
        return $this->map('mb_strtoupper');
    }

    public function split(string $delimiter, int $limit = null): StringCollectionInterface
    {
        $limit = \is_int($limit) ? $limit : \PHP_INT_MAX;

        return StringCollection::create(...\explode($delimiter, $this->value, $limit));
    }

    public function map(callable ...$callbacks): StringValueInterface
    {
        Assert::minCount($callbacks, 1);
        $value = $this->value;
        foreach ($callbacks as $callback) {
            $value = $callback($value);
            Assert::string($value, \sprintf('Callback should return a string, but %s was returned', \gettype($value)));
        }

        return new StringValue($value);
    }

    public function get(): string
    {
        return $this->value;
    }

    public function toScalar(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}