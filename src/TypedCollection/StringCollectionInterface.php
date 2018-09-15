<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

interface StringCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function push(string ...$values): self;

    public function remove(string ...$values): self;

    public function map(callable ...$callbacks): self;

    public function trimAll(): self;

    public function toUpperCaseAll(): self;

    public function toLowerCaseAll(): self;

    public function filter(callable $callback): self;

    public function filterNotEmpty(): self;

    public function filterNotIn(string ...$strings): self;

    public function unique(): self;

    public function duplicates(): self;

    public function includes(string $value): bool;

    public function join(string $separator): string;

    /**
     * @return string[]
     */
    public function values(): array;
}
