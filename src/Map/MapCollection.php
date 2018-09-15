<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Map;

use Miquido\DataStructure\Exception\ItemNotFoundException;
use Webmozart\Assert\Assert;

final class MapCollection implements MapCollectionInterface
{
    /**
     * @var MapInterface[]
     */
    private $data;

    public static function create(MapInterface ...$data): MapCollectionInterface
    {
        return new self(...$data);
    }

    public function __construct(MapInterface ...$data)
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
            function (MapInterface $map): array {
                return $map->toArray();
            },
            $this->data
        );
    }

    /**
     * @param callable $callback
     *
     * @throws ItemNotFoundException
     *
     * @return MapInterface
     */
    public function find(callable $callback): MapInterface
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
     * @param mixed  $value
     *
     * @throws ItemNotFoundException
     *
     * @return MapInterface
     */
    public function findByKeyAndValue(string $key, $value): MapInterface
    {
        return $this->find(function (MapInterface $item) use ($key, $value): bool {
            return $item->get($key) === $value;
        });
    }

    public function filter(callable $callback): MapCollectionInterface
    {
        return new self(...\array_filter(
            $this->data,
            function (MapInterface $item) use ($callback): bool {
                $result = $callback($item);
                Assert::boolean($result, \sprintf('Callback should return a boolean, but %s was returned', \gettype($result)));

                return $result;
            }
        ));
    }

    public function map(callable $callback): MapCollectionInterface
    {
        return new self(...\array_map(
            function (MapInterface $item) use ($callback): MapInterface {
                $mapped = $callback($item);
                Assert::isInstanceOf(
                    $mapped,
                    MapInterface::class,
                    \sprintf('Callback should return a MapInterface, but %s was returned', \is_object($mapped) ? \get_class($mapped) : \gettype($mapped))
                );

                return $mapped;
            },
            $this->data
        ));
    }

    /**
     * @return MapInterface[]
     */
    public function getAll(): array
    {
        return $this->data;
    }
}
