<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\Value\Scalar;

use Miquido\DataStructure\Value\Scalar\ScalarValue;
use PHPUnit\Framework\TestCase;

final class ScalarValueTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $scalar = ScalarValue::create(123);

        $this->assertSame(123, $scalar->getRawValue());
        $this->assertSame(123, $scalar->toScalar());
    }

    public function testIntCast(): void
    {
        $scalar = new ScalarValue(123);

        $this->assertSame(123, $scalar->int());
    }

    public function testIntCast_IntegerishInput(): void
    {
        $scalar = new ScalarValue('123');

        $this->assertSame(123, $scalar->int());
    }

    public function testStringCast(): void
    {
        $scalar = new ScalarValue('lorem ipsum');

        $this->assertSame('lorem ipsum', $scalar->string());
    }

    public function testFloatCast(): void
    {
        $scalar = new ScalarValue('1.12');

        $this->assertSame(1.12, $scalar->float());
    }

    public function testBoolCast(): void
    {
        $this->assertTrue(ScalarValue::create(true)->bool());
        $this->assertFalse(ScalarValue::create(false)->bool());

        $this->assertTrue(ScalarValue::create('true')->bool());
        $this->assertTrue(ScalarValue::create('yes')->bool());
        $this->assertTrue(ScalarValue::create('no empty')->bool());

        $this->assertFalse(ScalarValue::create('false')->bool());
        $this->assertFalse(ScalarValue::create('no')->bool());
        $this->assertFalse(ScalarValue::create('null')->bool());
        $this->assertFalse(ScalarValue::create('0')->bool());

        $this->assertTrue(ScalarValue::create('false')->bool(false));
        $this->assertTrue(ScalarValue::create('no')->bool(false));
        $this->assertTrue(ScalarValue::create('null')->bool(false));
    }

    public function testDateTime(): void
    {
        $date = new \DateTime();
        $scalar = new ScalarValue($date);

        $this->assertSame($date->format(\DATE_ATOM), $scalar->dateTime()->format(\DATE_ATOM));
        $this->assertSame($date->format('Y-m-dfH:i:s'), $scalar->dateTime()->format('Y-m-dfH:i:s'));
    }

    public function testDateTime_StringInput(): void
    {
        $scalar = new ScalarValue('2018-09-15 11:37:21');

        $this->assertSame('2018-09-15 11:37:21', $scalar->dateTime()->format('Y-m-d H:i:s'));
    }

    public function testDateTime_IntInput(): void
    {
        $scalar = new ScalarValue(1537011441);

        $this->assertSame('2018-09-15 11:37:21', $scalar->dateTime()->format('Y-m-d H:i:s'));
    }

    public function testDateTime_InvalidInput(): void
    {
        $scalar = new ScalarValue(true);

        $this->expectException(\InvalidArgumentException::class);
        $scalar->dateTime();
    }
}
