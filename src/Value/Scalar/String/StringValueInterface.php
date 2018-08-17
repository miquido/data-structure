<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar\String;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;

interface StringValueInterface extends ScalarConvertibleInterface
{
    public function get(): string;

    public function trim(string $charList = null): StringValueInterface;
    public function toLower(): StringValueInterface;
    public function toUpper(): StringValueInterface;

    public function split(string $delimiter, int $limit = null): StringCollectionInterface;

    public function map(callable $callback): StringValueInterface;
}