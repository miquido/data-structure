<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\Map\Map;
use Miquido\DataStructure\Map\MapInterface;
use Webmozart\Assert\Assert;

final class StringCollection implements StringCollectionInterface
{
    /**
     * @var string[]
     */
    private $strings;

    public static function create(string ...$strings): StringCollectionInterface
    {
        return new self(...$strings);
    }

    public function __construct(string ...$strings)
    {
        $this->strings = $strings;
    }

    public function push(string ...$values): StringCollectionInterface
    {
        return new self(...\array_merge($this->strings, $values));
    }

    public function remove(string ...$values): StringCollectionInterface
    {
        return new self(...\array_filter($this->strings, function (string $value) use ($values) {
            return !\in_array($value, $values, true);
        }));
    }

    public function map(callable ...$callbacks): StringCollectionInterface
    {
        Assert::minCount($callbacks, 1);

        return new self(...\array_map(function (string $string) use ($callbacks): string {
            $value = $string;
            foreach ($callbacks as $callback) {
                $value = $callback($value);
                Assert::string($value, 'Callback should return a string');
            }

            return $value;
        }, $this->strings));
    }

    public function trimAll(): StringCollectionInterface
    {
        return $this->map('trim');
    }

    public function toUpperCaseAll(): StringCollectionInterface
    {
        return $this->map('mb_strtoupper');
    }

    public function toLowerCaseAll(): StringCollectionInterface
    {
        return $this->map('mb_strtolower');
    }

    public function filter(callable $callback): StringCollectionInterface
    {
        return new self(...\array_filter($this->strings, function ($string) use ($callback): bool {
            $result = $callback($string);
            Assert::boolean($result, 'Callback should return a boolean');

            return $result;
        }));
    }

    public function filterNotEmpty(): StringCollectionInterface
    {
        return $this->filter(function (string $value): bool {
            return \mb_strlen($value) > 0; // remove empty values
        });
    }

    public function unique(): StringCollectionInterface
    {
        return new self(...\array_reduce(
            $this->strings,
            function (array $carry, string $item): array {
                if (!\in_array($item, $carry, true)) {
                    $carry[] = $item;
                }

                return $carry;
            },
            []
        ));
    }

    public function duplicates(): StringCollectionInterface
    {
        /** @var MapInterface $grouped */
        $grouped = \array_reduce(
            $this->strings,
            function (MapInterface $group, string $string): MapInterface {
                return $group->set($string, $group->has($string) ? 1 + $group->getValue($string)->int() : 1);
            },
            new Map()
        );

        return $grouped->filterByValues(function (int $value): bool {
            return $value > 1;
        })->keys();
    }

    public function includes(string $value): bool
    {
        return \in_array($value, $this->strings, true);
    }

    public function filterNotIn(string ...$strings): StringCollectionInterface
    {
        return $this->filter(function (string $value) use ($strings): bool {
            return !\in_array($value, $strings, true);
        });
    }

    public function join(string $separator): string
    {
        return \implode($separator, $this->strings);
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return $this->strings;
    }

    /**
     * @return string[]
     */
    public function values(): array
    {
        return $this->strings;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->strings);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->strings);
    }
}
