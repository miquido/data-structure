<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

interface IntegerCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function push(int ...$numbers): IntegerCollectionInterface;
    public function unique(): IntegerCollectionInterface;
    public function includes(int $number): bool;

    /**
     * @return int[]
     */
    public function values(): array;
}