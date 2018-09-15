<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar\String;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;

interface StringValueInterface extends ScalarConvertibleInterface
{
    public function get(): string;

    public function trim(string $charList = null): self;

    public function toLower(): self;

    public function toUpper(): self;

    public function split(string $delimiter, int $limit = null): StringCollectionInterface;

    public function map(callable ...$callbacks): self;

    public function __toString(): string;
}
