<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\TypedCollection\IntegerCollection;
use Miquido\DataStructure\TypedCollection\IntegerCollectionInterface;
use PHPUnit\Framework\TestCase;

final class IntegerCollectionTest extends TestCase
{
    public function testIntegerCollection(): void
    {
        $collection = new IntegerCollection(1, 2, 3, 3);

        $this->assertCount(4, $collection);
        $this->assertTrue($collection->includes(1));
        $this->assertTrue($collection->includes(2));
        $this->assertTrue($collection->includes(3));
        $this->assertFalse($collection->includes(0));
        $this->assertFalse($collection->includes(4));

        $unique = $collection->unique();

        $this->assertNotSame($collection, $unique);
        $this->assertCount(4, $collection);
        $this->assertCount(3, $unique);

        $extended = $collection->push(5, 6);

        $this->assertNotSame($collection, $extended);
        $this->assertCount(4, $collection);
        $this->assertCount(6, $extended);
        $this->assertTrue($extended->includes(5));
        $this->assertTrue($extended->includes(6));
        $this->assertFalse($collection->includes(5));
        $this->assertFalse($collection->includes(6));
    }

    public function testStaticCreate(): void
    {
        $collection = IntegerCollection::create(1, 2, 3);

        $this->assertInstanceOf(IntegerCollectionInterface::class, $collection);
        $this->assertCount(3, $collection);
        $this->assertTrue($collection->includes(1));
        $this->assertTrue($collection->includes(2));
        $this->assertTrue($collection->includes(3));
        $this->assertFalse($collection->includes(0));
        $this->assertFalse($collection->includes(4));

    }
}