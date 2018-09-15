<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Map;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\TypedCollection\StringCollection;
use Miquido\DataStructure\TypedCollection\StringCollectionInterface;
use Miquido\DataStructure\Value\Value;
use Miquido\DataStructure\Value\ValueInterface;
use Webmozart\Assert\Assert;

final class Map implements MapInterface
{
    /**
     * @var array
     */
    private $data;

    public static function create($values = null): MapInterface
    {
        return new self($values);
    }

    /**
     * @param array|MapInterface|null $values
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($values = null)
    {
        if (null === $values) {
            $values = [];
        }
        if ($values instanceof ArrayConvertibleInterface) {
            $values = $values->toArray();
        }

        Assert::isArray($values, \sprintf('Invalid input type "%s" (allowed: array, %s)', \gettype($values), ArrayConvertibleInterface::class));
        Assert::allString(\array_keys($values), 'Only string keys are allowed');

        $this->data = $values;
    }

    public function set(string $key, $value): MapInterface
    {
        return new self(\array_merge($this->data, [$key => $value]));
    }

    public function get(string $key, $default = null, bool $nullDefault = false)
    {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (null !== $default || (null === $default && $nullDefault)) {
            return $default;
        }

        throw new \OutOfBoundsException(\sprintf('Key "%s" does not exist', $key));
    }

    public function getValue(string $key, $default = null, bool $nullDefault = false): ValueInterface
    {
        return new Value($this->get($key, $default, $nullDefault));
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->data);
    }

    public function hasOneOf(string ...$keys): bool
    {
        foreach ($keys as $name) {
            if ($this->has($name)) {
                return true;
            }
        }

        return false;
    }

    public function hasAll(string ...$keys): bool
    {
        Assert::minCount($keys, 1, 'Please provide at least one key');
        foreach ($keys as $name) {
            if (!$this->has($name)) {
                return false;
            }
        }

        return true;
    }

    public function remove(string ...$keysToRemove): MapInterface
    {
        return new self(\array_filter($this->data, function (/* @noinspection PhpUnusedParameterInspection */$value, $key) use ($keysToRemove): bool {
            return !\in_array($key, $keysToRemove, true);
        }, \ARRAY_FILTER_USE_BOTH));
    }

    public function pick(string ...$keysToPick): MapInterface
    {
        return $this->filterByKeys(function (string $key) use ($keysToPick): bool {
            return \in_array($key, $keysToPick, true);
        });
    }

    public function rename(string $key, string $newName): MapInterface
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException(\sprintf('Key "%s" does not exist', $key));
        }
        if ($this->has($newName)) {
            throw new \InvalidArgumentException(\sprintf('Key "%s" already exists', $newName));
        }

        return $this->remove($key)->set($newName, $this->get($key));
    }

    public function filter(callable $callback): MapInterface
    {
        return new self(\array_filter($this->data, function ($value, string $key) use ($callback): bool {
            $result = $callback($value, $key);
            Assert::boolean($result, \sprintf('Callback should return boolean, got %s', \gettype($result)));

            return $result;
        }, \ARRAY_FILTER_USE_BOTH));
    }

    public function filterByValues(callable $callback): MapInterface
    {
        return new self(\array_filter($this->data, function ($value) use ($callback): bool {
            $result = $callback($value);
            Assert::boolean($result, \sprintf('Callback should return boolean, got %s', \gettype($result)));

            return $result;
        }));
    }

    public function filterByKeys(callable $callback): MapInterface
    {
        return new self(\array_filter($this->data, function (string $key) use ($callback): bool {
            $result = $callback($key);
            Assert::boolean($result, \sprintf('Callback should return boolean, got %s', \gettype($result)));

            return $result;
        }, \ARRAY_FILTER_USE_KEY));
    }

    public function merge(MapInterface $map): MapInterface
    {
        return new self(\array_merge($this->toArray(), $map->toArray()));
    }

    public function equals(MapInterface $map): bool
    {
        if ($this->keys()->count() === $map->keys()->count()) {
            foreach ($this as $key => $value) {
                if (!$map->has($key) || $map->get($key) !== $value) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function keys(): StringCollectionInterface
    {
        return new StringCollection(...\array_keys($this->data));
    }

    public function values(): array
    {
        return \array_values($this->data);
    }

    public function mapKeys(callable $callback): MapInterface
    {
        $transformed = new self([]);
        foreach ($this->data as $key => $value) {
            $mappedKey = $callback($key);
            Assert::string($mappedKey, 'Callback should return a string');

            if ($transformed->has($mappedKey)) {
                throw new \LogicException(\sprintf('Duplicated key "%s"', $mappedKey));
            }

            $transformed = $transformed->set($mappedKey, $value);
        }

        return $transformed;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            if ($value instanceof ArrayConvertibleInterface) {
                $value = $value->toArray();
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException(\sprintf('%s - method not allowed. Please use "set" method.', __METHOD__));
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        throw new \LogicException(\sprintf('%s - method not allowed. Please use "delete" method.', __METHOD__));
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }
}
