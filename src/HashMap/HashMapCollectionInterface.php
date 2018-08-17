<?php

declare(strict_types=1);

namespace Miquido\DataStructure\HashMap;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\Exception\ItemNotFoundException;

interface HashMapCollectionInterface extends \Countable, \IteratorAggregate, ArrayConvertibleInterface
{
    /**
     * @param callable $callback
     * @return HashMapInterface
     * @throws ItemNotFoundException
     */
    public function find(callable $callback): HashMapInterface;

    /**
     * @param string $key
     * @param $value
     * @return HashMapInterface
     * @throws ItemNotFoundException
     */
    public function findByKeyAndValue(string $key, $value): HashMapInterface;

    public function filter(callable $callback): HashMapCollectionInterface;

    public function map(callable $callback): HashMapCollectionInterface;

    /**
     * @return HashMapInterface[]
     */
    public function getAll(): array;
}