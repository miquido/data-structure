<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests\Value\Scalar\String;

use Miquido\DataStructure\Value\Scalar\String\StringValue;
use PHPUnit\Framework\TestCase;

final class StringValueTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $string = StringValue::create('lorem ipsum');

        $this->assertSame('lorem ipsum', $string->get());
        $this->assertSame('lorem ipsum', $string->toScalar());
        $this->assertSame('lorem ipsum', (string) $string);
    }

    public function testCreateFromNotString(): void
    {
        $string = StringValue::create(123.45);

        $this->assertSame('123.45', $string->get());
    }

    public function testCreateFromNotString_ObjectWithToString(): void
    {
        $string = StringValue::create(new class() {
            public function __toString(): string
            {
                return 'lorem ipsum';
            }
        });

        $this->assertSame('lorem ipsum', $string->get());
    }

    public function testToLower(): void
    {
        $string = new StringValue(' LOREM IPSUM ');

        $lowerCased = $string->toLower();

        $this->assertSame(' LOREM IPSUM ', $string->get());
        $this->assertSame(' lorem ipsum ', $lowerCased->get());
    }

    public function testToUpper(): void
    {
        $string = new StringValue(' lorem ipsum ');

        $upperCased = $string->toUpper();

        $this->assertSame(' lorem ipsum ', $string->get());
        $this->assertSame(' LOREM IPSUM ', $upperCased->get());
    }

    public function testMap_multipleCallbacks(): void
    {
        $string = new StringValue(' LOREM IPSUM ');

        $trimmedLowerCased = $string->map('trim', 'strtolower');

        $this->assertSame(' LOREM IPSUM ', $string->get());
        $this->assertSame('lorem ipsum', $trimmedLowerCased->get());
    }

    public function testTrim(): void
    {
        $string = new StringValue('
            lorem ipsum
        ');

        $trimmed = $string->trim();

        $this->assertSame('
            lorem ipsum
        ', $string->get());
        $this->assertSame('lorem ipsum', $trimmed->get());
    }

    public function testTrim_CustomChars(): void
    {
        $string = new StringValue('lorem ipsum');

        $trimmed = $string->trim('lm');

        $this->assertSame('lorem ipsum', $string->get());
        $this->assertSame('orem ipsu', $trimmed->get());
    }

    public function testSplit(): void
    {
        $string = new StringValue('lorem ipsum dolor sit amet');

        $strings = $string->split(' ');
        $this->assertCount(5, $strings);
        $this->assertContains('lorem', $strings);
        $this->assertContains('ipsum', $strings);
        $this->assertContains('dolor', $strings);
        $this->assertContains('sit', $strings);
        $this->assertContains('amet', $strings);
    }

    public function testSplit_EmptyDelimeter(): void
    {
        $string = new StringValue('lorem ipsum dolor sit amet');
        $this->expectException(\InvalidArgumentException::class);
        $string->split('');
    }

    public function testSplit_WithLimit(): void
    {
        $string = new StringValue('lorem ipsum dolor sit amet');

        $strings = $string->split(' ', 3);
        $this->assertCount(3, $strings);
        $this->assertContains('lorem', $strings);
        $this->assertContains('ipsum', $strings);
        $this->assertContains('dolor sit amet', $strings);
    }
}
