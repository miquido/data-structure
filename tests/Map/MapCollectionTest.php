<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\Map;

use Miquido\DataStructure\Exception\ItemNotFoundException;
use Miquido\DataStructure\Map\Map;
use Miquido\DataStructure\Map\MapCollection;
use Miquido\DataStructure\Map\MapInterface;
use PHPUnit\Framework\TestCase;

final class MapCollectionTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection = MapCollection::create($map1, $map2, $map3);

        $this->assertCount(3, $collection);
        $this->assertSame([['id' => 1], ['id' => 2], ['id' => 3]], $collection->toArray());
        $this->assertSame([$map1, $map2, $map3], $collection->getAll());
    }

    public function testIteratorWorks(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection = MapCollection::create($map1, $map2, $map3);

        $iterations = 0;
        foreach ($collection as $map) {
            ++$iterations;
            $this->assertContains($map, [$map1, $map2, $map3]);
        }

        $this->assertSame(3, $iterations);
    }

    public function testFind(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection = MapCollection::create($map1, $map2, $map3);

        $map = $collection->find(function (MapInterface $map): bool {
            return $map->getValue('id')->int() >= 2;
        });

        $this->assertSame($map2, $map);

        $this->expectException(ItemNotFoundException::class);
        $collection->find(function (MapInterface $map): bool {
            return 4 === $map->getValue('id');
        });
    }

    public function testFindByKeyAndValue(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection = MapCollection::create($map1, $map2, $map3);

        $map = $collection->findByKeyAndValue('id', 2);
        $this->assertSame($map, $map2);
    }

    public function testFilter(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection = MapCollection::create($map1, $map2, $map3);

        $filtered = $collection->filter(function (MapInterface $map): bool {
            return $map->getValue('id')->int() > 1;
        });

        $this->assertCount(3, $collection);
        $this->assertCount(2, $filtered);
        $this->assertSame([$map2, $map3], $filtered->getAll());
    }

    public function testMap(): void
    {
        $map1 = new Map(['id' => 1]);
        $map2 = new Map(['id' => 2]);
        $map3 = new Map(['id' => 3]);
        $collection1 = MapCollection::create($map1, $map2, $map3);

        $collection2 = $collection1->map(function (MapInterface $map): MapInterface {
            return $map->set('id', $map->getValue('id')->int() * 10);
        });

        $this->assertSame([$map1, $map2, $map3], $collection1->getAll());
        $this->assertSame([['id' => 1], ['id' => 2], ['id' => 3]], $collection1->toArray());
        $this->assertSame([['id' => 10], ['id' => 20], ['id' => 30]], $collection2->toArray());
    }
}
