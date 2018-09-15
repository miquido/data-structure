<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

interface NumberCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function push(float ...$numbers): NumberCollectionInterface;

    public function unique(): NumberCollectionInterface;

    public function includes(float $number): bool;

    /**
     * @return float[]
     */
    public function values(): array;
}
