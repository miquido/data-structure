<?php

declare(strict_types=1);

namespace Miquido\DataStructure\HashMap;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;
use Miquido\DataStructure\Value\ValueInterface;

interface HashMapInterface extends \ArrayAccess, \IteratorAggregate, ArrayConvertibleInterface, \Countable
{
    public function set(string $key, $value): HashMapInterface;

    /**
     * @param string $key
     * @param null $default
     * @param bool $nullDefault
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function get(string $key, $default = null, bool $nullDefault = false);

    /**
     * @param string $key
     * @return ValueInterface
     * @throws \OutOfBoundsException
     */
    public function getValue(string $key): ValueInterface;
    public function has(string $key): bool;
    public function hasOneOf(string ...$keys): bool;
    public function hasAll(string ...$keys): bool;
    public function remove(string ...$keysToRemove): HashMapInterface;
    public function pick(string ...$keysToPick): HashMapInterface;
    public function rename(string $key, string $newName): HashMapInterface;

    public function filter(callable $callback): HashMapInterface;
    public function filterByValues(callable $callback): HashMapInterface;
    public function filterByKeys(callable $callback): HashMapInterface;

    public function merge(HashMapInterface $map): HashMapInterface;
    public function equals(HashMapInterface $map): bool;

    public function keys(): StringCollectionInterface;
    public function values(): array;

    public function mapKeys(callable $callback): HashMapInterface;
}