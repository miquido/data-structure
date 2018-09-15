<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests\Value\Collection;

use Miquido\DataStructure\Value\Collection\CollectionValue;
use PHPUnit\Framework\TestCase;

final class CollectionValueTest extends TestCase
{
    public function testGet(): void
    {
        $values = [1, 2, 3];

        $collection = new CollectionValue($values);

        $this->assertCount(3, $collection);
        $this->assertSame($values, $collection->get());
        $this->assertSame($values, $collection->toArray());
    }

    public function testStrings(): void
    {
        $collection = new CollectionValue(['lorem', 'ipsum']);

        $strings = $collection->strings();

        $this->assertCount(2, $strings);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
    }

    public function testNumbers(): void
    {
        $collection = new CollectionValue([1.1, 2.2, 3.3]);

        $numbers = $collection->numbers();

        $this->assertCount(3, $numbers);
        $this->assertTrue($numbers->includes(1.1));
        $this->assertTrue($numbers->includes(2.2));
        $this->assertTrue($numbers->includes(3.3));
    }

    public function testIntegers(): void
    {
        $collection = new CollectionValue([1, 2, 3]);

        $numbers = $collection->integers();

        $this->assertCount(3, $numbers);
        $this->assertTrue($numbers->includes(1));
        $this->assertTrue($numbers->includes(2));
        $this->assertTrue($numbers->includes(3));
    }

    public function testObjects(): void
    {
        $collection = new CollectionValue([new \stdClass(), new \stdClass()]);

        $objects = $collection->objects();

        $this->assertCount(2, $objects);
    }

    public function testIterationWorks(): void
    {
        $collection = new CollectionValue(['name' => 'John', 'surname' => 'Smith']);

        $iterations = 0;
        foreach ($collection as $key => $value) {
            ++$iterations;
            $this->assertContains($key, ['name', 'surname']);
            $this->assertContains($value, ['John', 'Smith']);
        }

        $this->assertEquals(2, $iterations);
    }

    public function testKeys(): void
    {
        $collection = new CollectionValue(['name' => 'John', 'surname' => 'Smith', 123]);

        $keys = $collection->keys();

        $this->assertCount(3, $keys);
        $this->assertContains('name', $keys);
        $this->assertContains('surname', $keys);
        $this->assertContains(0, $keys);
    }

    public function testValues(): void
    {
        $collection = new CollectionValue(['name' => 'John', 'surname' => 'Smith', 123]);

        $values = $collection->values();

        $this->assertCount(3, $values);
        $this->assertContains('John', $values);
        $this->assertContains('Smith', $values);
        $this->assertContains(123, $values);
    }
}
