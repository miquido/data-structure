<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Collection;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\TypedCollection\IntegerCollectionInterface;
use Miquido\DataStructure\TypedCollection\NumberCollectionInterface;
use Miquido\DataStructure\TypedCollection\ObjectCollectionInterface;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;

interface CollectionValueInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function strings(): StringCollectionInterface;

    public function numbers(): NumberCollectionInterface;

    public function integers(): IntegerCollectionInterface;

    public function objects(): ObjectCollectionInterface;

    public function keys(): array;

    public function values(): array;

    public function get(): array;
}
