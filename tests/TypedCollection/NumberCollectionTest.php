<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\TypedCollection;

namespace Tests\Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\TypedCollection\NumberCollection;
use PHPUnit\Framework\TestCase;

final class NumberCollectionTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $integers = NumberCollection::create(1.1, 2.2, 3.3);

        $this->assertCount(3, $integers);
        $this->assertSame([1.1, 2.2, 3.3], $integers->values());
        $this->assertSame([1.1, 2.2, 3.3], $integers->toArray());
        $this->assertTrue($integers->includes(1.1));
        $this->assertTrue($integers->includes(2.2));
        $this->assertTrue($integers->includes(3.3));
    }

    public function testIteratorWorks(): void
    {
        $integers = new NumberCollection(1.1, 2, 3.3);

        $iterations = 0;
        foreach ($integers as $i) {
            ++$iterations;
            $this->assertContains($i, [1.1, 2, 3.3]);
        }

        $this->assertSame(3, $iterations);
    }

    public function testPush(): void
    {
        $integers = new NumberCollection(1.1, 2.2, 3.3);
        $integers2 = $integers->push(4.4);

        $this->assertCount(3, $integers);
        $this->assertTrue($integers->includes(1.1));
        $this->assertTrue($integers->includes(2.2));
        $this->assertTrue($integers->includes(3.3));

        $this->assertCount(4, $integers2);
        $this->assertTrue($integers2->includes(1.1));
        $this->assertTrue($integers2->includes(2.2));
        $this->assertTrue($integers2->includes(3.3));
        $this->assertTrue($integers2->includes(4.4));
    }

    public function testUnique(): void
    {
        $integers = new NumberCollection(1, 2, 3, 3, 1, 2);
        $unique = $integers->unique();

        $this->assertCount(6, $integers);
        $this->assertTrue($integers->includes(1));
        $this->assertTrue($integers->includes(2));
        $this->assertTrue($integers->includes(3));

        $this->assertCount(3, $unique);
        $this->assertTrue($unique->includes(1));
        $this->assertTrue($unique->includes(2));
        $this->assertTrue($unique->includes(3));
    }
}