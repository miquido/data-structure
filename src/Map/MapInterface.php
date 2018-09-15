<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Map;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;
use Miquido\DataStructure\Value\ValueInterface;

interface MapInterface extends \ArrayAccess, \IteratorAggregate, ArrayConvertibleInterface, \Countable
{
    public function set(string $key, $value): self;

    /**
     * @param string     $key
     * @param null|mixed $default
     * @param bool       $nullDefault
     *
     * @throws \OutOfBoundsException
     *
     * @return mixed
     */
    public function get(string $key, $default = null, bool $nullDefault = false);

    /**
     * @param string $key
     * @param null   $default
     * @param bool   $nullDefault
     *
     * @throws \OutOfBoundsException
     *
     * @return ValueInterface
     */
    public function getValue(string $key, $default = null, bool $nullDefault = false): ValueInterface;

    public function has(string $key): bool;

    public function hasOneOf(string ...$keys): bool;

    public function hasAll(string ...$keys): bool;

    public function pick(string ...$keysToPick): self;

    public function remove(string ...$keysToRemove): self;

    public function rename(string $key, string $newName): self;

    public function filter(callable $callback): self;

    public function filterByValues(callable $callback): self;

    public function filterByKeys(callable $callback): self;

    public function merge(self $map): self;

    public function equals(self $map): bool;

    public function keys(): StringCollectionInterface;

    public function values(): array;

    public function mapKeys(callable $callback): self;
}
