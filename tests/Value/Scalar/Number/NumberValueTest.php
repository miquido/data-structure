<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\Value\Scalar\Number;

use Miquido\DataStructure\Value\Scalar\Number\NumberValue;
use PHPUnit\Framework\TestCase;

final class NumberValueTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $number = NumberValue::create(123.45);

        $this->assertSame(123.45, $number->get());
        $this->assertSame(123.45, $number->float());
        $this->assertSame(123.45, $number->toScalar());
        $this->assertSame(123, $number->int());
    }

    public function testMap(): void
    {
        $number = new NumberValue(-123.5);

        $abs = $number->map('abs');

        $this->assertNotSame($number, $abs);
        $this->assertSame(123.5, $abs->get());
    }
}