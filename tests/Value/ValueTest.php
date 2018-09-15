<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests\Value;

use Miquido\DataStructure\Value\Value;
use PHPUnit\Framework\TestCase;

final class ValueTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $value = Value::create(123);

        $this->assertEquals(123, $value->getRawValue());
    }

    public function testToMap(): void
    {
        $value = new Value(['name' => 'John', 'surname' => 'Smith']);

        $map = $value->toMap();
        $this->assertCount(2, $map);
        $this->assertTrue($map->has('name'));
        $this->assertTrue($map->has('surname'));
        $this->assertEquals('John', $map->get('name'));
        $this->assertEquals('Smith', $map->get('surname'));
    }

    public function testToMap_SourceNotArray(): void
    {
        $value = new Value(123);

        $this->expectException(\InvalidArgumentException::class);
        $value->toMap();
    }

    public function testToMap_SourceInvalidArray(): void
    {
        $value = new Value(['name' => 'John', 'surname' => 'Smith', 123]);

        $this->expectException(\InvalidArgumentException::class);
        $value->toMap();
    }

    public function testToCollectionValue(): void
    {
        $value = new Value([1, 2, 3]);

        $collection = $value->toCollectionValue();
        $this->assertCount(3, $collection);
    }

    public function testToCollectionValue_ScalarInput(): void
    {
        $value = new Value(123);

        $collection = $value->toCollectionValue();
        $this->assertCount(1, $collection);
    }

    public function testToCollectionValue_ScalarInput_NoCast(): void
    {
        $value = new Value(123);

        $this->expectException(\InvalidArgumentException::class);
        $value->toCollectionValue(false);
    }

    public function testToScalarValue(): void
    {
        $value = new Value(123);

        $scalar = $value->toScalarValue();
        $this->assertEquals(123, $scalar->getRawValue());
    }

    public function testToScalarValue_NoScalarInput(): void
    {
        $value = new Value([1, 2, 3]);

        $this->expectException(\InvalidArgumentException::class);
        $value->toScalarValue();
    }

    public function testToStringValue(): void
    {
        $value = new Value('lorem ipsum');

        $string = $value->toStringValue();
        $this->assertEquals('lorem ipsum', $string->get());
    }

    public function testToNumberValue(): void
    {
        $value = new Value(123);

        $number = $value->toNumberValue();
        $this->assertEquals(123, $number->get());
    }

    public function testStringCast(): void
    {
        $value = new Value('lorem ipsum');

        $string = $value->string();

        $this->assertEquals('lorem ipsum', $string);
    }

    public function testFloatCast(): void
    {
        $value = new Value(1.1);

        $float = $value->float();

        $this->assertEquals(1.1, $float);
    }

    public function testBoolCast(): void
    {
        $value = new Value(true);

        $bool = $value->bool();
        $this->assertTrue($bool);
    }

    public function testDateTimeCast(): void
    {
        $value = new Value('2018-09-15');

        $date = $value->dateTime();

        $this->assertEquals('2018-09-15', $date->format('Y-m-d'));
    }
}
