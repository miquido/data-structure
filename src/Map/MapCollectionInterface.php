<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Map;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\Exception\ItemNotFoundException;

interface MapCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    /**
     * @param callable $callback
     *
     * @throws ItemNotFoundException
     *
     * @return MapInterface
     */
    public function find(callable $callback): MapInterface;

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @throws ItemNotFoundException
     *
     * @return MapInterface
     */
    public function findByKeyAndValue(string $key, $value): MapInterface;

    public function filter(callable $callback): self;

    public function map(callable $callback): self;

    /**
     * @return MapInterface[]
     */
    public function getAll(): array;
}
