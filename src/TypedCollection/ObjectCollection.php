<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\HashMap\HashMap;
use Miquido\DataStructure\HashMap\HashMapInterface;
use Webmozart\Assert\Assert;

final class ObjectCollection implements ObjectCollectionInterface
{
    private $objects;

    public function __construct(object ...$objects)
    {
        $this->objects = $objects;
    }

    public static function fromIterator(iterable $iterable): ObjectCollectionInterface
    {
        $objects = [];
        foreach ($iterable as $object) {
            Assert::object($object, \sprintf('Accepts only objects, got %s', \gettype($object)));
            $objects[] = $object;
        }

        return new ObjectCollection(...$objects);
    }

    public function toMap(callable $keyProvider): HashMapInterface
    {
        return \array_reduce(
            $this->objects,
            function (HashMapInterface $map, object $object) use ($keyProvider): HashMapInterface {
                $key = $keyProvider($object);
                if (!\is_string($key)) {
                    throw new \RuntimeException(\sprintf('Key provider should return a string, got %s', \gettype($key)));
                }
                if ($map->has($key)) {
                    throw new \LogicException(\sprintf('Key "%s" already in use', $key));
                }

                return $map->set($key, $object);
            },
            new HashMap()
        );
    }

    public function getAll(): array
    {
        return $this->objects;
    }

    public function count(): int
    {
        return \count($this->objects);
    }
}