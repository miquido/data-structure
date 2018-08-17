<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\TypedCollection;

namespace Tests\Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\TypedCollection\NumberCollection;
use Miquido\DataStructure\TypedCollection\NumberCollectionInterface;
use PHPUnit\Framework\TestCase;

final class NumberCollectionTest extends TestCase
{
    public function testNumberCollectionCollection(): void
    {
        $collection = new NumberCollection(1, 2.5, 3.1, 3.1);

        $this->assertCount(4, $collection);
        $this->assertTrue($collection->includes(1));
        $this->assertTrue($collection->includes(2.5));
        $this->assertTrue($collection->includes(3.1));
        $this->assertFalse($collection->includes(0));
        $this->assertFalse($collection->includes(2));
        $this->assertFalse($collection->includes(3));
        $this->assertFalse($collection->includes(4));

        $unique = $collection->unique();

        $this->assertNotSame($collection, $unique);
        $this->assertCount(4, $collection);
        $this->assertCount(3, $unique);
        $this->assertTrue($unique->includes(1));
        $this->assertTrue($unique->includes(2.5));
        $this->assertTrue($unique->includes(3.1));

        $extended = $collection->push(5.5, 6);

        $this->assertNotSame($collection, $extended);
        $this->assertCount(4, $collection);
        $this->assertCount(6, $extended);
        $this->assertTrue($extended->includes(5.5));
        $this->assertTrue($extended->includes(6));
        $this->assertFalse($collection->includes(5.5));
        $this->assertFalse($collection->includes(6));
    }

    public function testStaticCreate(): void
    {
        $collection = NumberCollection::create(1, 2, 3);

        $this->assertInstanceOf(NumberCollectionInterface::class, $collection);
        $this->assertCount(3, $collection);
        $this->assertTrue($collection->includes(1));
        $this->assertTrue($collection->includes(2));
        $this->assertTrue($collection->includes(3));
        $this->assertFalse($collection->includes(0));
        $this->assertFalse($collection->includes(4));

    }
}