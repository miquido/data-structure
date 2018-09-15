<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\ArrayConvertibleInterface;

interface NumberCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    public function push(float ...$numbers): self;

    public function unique(): self;

    public function includes(float $number): bool;

    /**
     * @return float[]
     */
    public function values(): array;
}
