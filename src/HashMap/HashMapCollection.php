<?php

declare(strict_types=1);

namespace Miquido\DataStructure\HashMap;

use Miquido\DataStructure\Exception\ItemNotFoundException;
use Webmozart\Assert\Assert;

final class HashMapCollection implements HashMapCollectionInterface
{
    /**
     * @var HashMapInterface[]
     */
    private $data;

    public function __construct(HashMapInterface ...$data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->data);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function toArray(): array
    {
        return \array_map(
            function (HashMapInterface $map): array {
                return $map->toArray();
            },
            $this->data
        );
    }

    /**
     * @param callable $callback
     * @return HashMapInterface
     * @throws ItemNotFoundException
     */
    public function find(callable $callback): HashMapInterface
    {
        foreach ($this->data as $item) {
            $result = $callback($item);
            Assert::boolean($result, \sprintf('Callback should return a boolean, but %s was returned', \gettype($result)));

            if ($result) {
                return $item;
            }
        }

        throw new ItemNotFoundException('No item found.');
    }

    /**
     * @param string $key
     * @param $value
     * @return HashMapInterface
     * @throws ItemNotFoundException
     */
    public function findByKeyAndValue(string $key, $value): HashMapInterface
    {
        return $this->find(function (HashMapInterface $item) use ($key, $value): bool {
            return $item->get($key) === $value;
        });
    }

    public function filter(callable $callback): HashMapCollectionInterface
    {
        return new HashMapCollection(...\array_filter(
            $this->data,
            function (HashMapInterface $item) use ($callback): bool {
                $result = $callback($item);
                Assert::boolean($result, \sprintf('Callback should return a boolean, but %s was returned', \gettype($result)));

                return $result;
            }
        ));
    }

    public function map(callable $callback): HashMapCollectionInterface
    {
        return new HashMapCollection(...\array_map(
            function (HashMapInterface $item) use ($callback): HashMapInterface {
                $mapped = $callback($item);
                Assert::isInstanceOf(
                    $mapped,
                    HashMapInterface::class,
                    \sprintf('Callback should return a HashMapInterface, but %s was returned', \is_object($mapped) ? \get_class($mapped) : \gettype($mapped))
                );

                return $mapped;
            },
            $this->data
        ));
    }

    /**
     * @return HashMapInterface[]
     */
    public function getAll(): array
    {
        return $this->data;
    }
}