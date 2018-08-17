<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

interface StringCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function push(string ...$values): StringCollectionInterface;

    public function remove(string ...$values): StringCollectionInterface;

    /**
     * @param callable $callback
     * @return StringCollectionInterface
     */
    public function map(callable $callback): StringCollectionInterface;
    public function trimAll(): StringCollectionInterface;
    public function toUpperCaseAll(): StringCollectionInterface;
    public function toLowerCaseAll(): StringCollectionInterface;

    public function filter(callable $callback): StringCollectionInterface;
    public function filterNotEmpty(): StringCollectionInterface;

    public function unique(): StringCollectionInterface;
    public function duplicates(): StringCollectionInterface;

    public function includes(string $value): bool;

    public function notIn(string ...$strings): StringCollectionInterface;

    /**
     * @return string[]
     */
    public function values(): array;

    public function join(string $separator): string;
}