<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\TypedCollection\StringCollection;
use PHPUnit\Framework\TestCase;

final class StringCollectionTest extends TestCase
{
    public function testStringCollection_push(): void
    {
        $strings = new StringCollection('lorem', 'ipsum');
        $updated = $strings->push('dolor', 'sit', 'amet');

        $this->assertNotSame($strings, $updated);
        $this->assertCount(2, $strings);
        $this->assertCount(5, $updated);

        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertFalse($strings->includes('dolor'));
        $this->assertFalse($strings->includes('sit'));
        $this->assertFalse($strings->includes('amet'));

        $this->assertTrue($updated->includes('lorem'));
        $this->assertTrue($updated->includes('ipsum'));
        $this->assertTrue($updated->includes('dolor'));
        $this->assertTrue($updated->includes('sit'));
        $this->assertTrue($updated->includes('amet'));
    }

    public function testStringCollection_remove(): void
    {
        $strings = new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $removed = $strings->remove('dolor');

        $this->assertNotSame($strings, $removed);
        $this->assertCount(5, $strings);
        $this->assertCount(4, $removed);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertTrue($strings->includes('dolor'));
        $this->assertTrue($strings->includes('sit'));
        $this->assertTrue($strings->includes('amet'));
        $this->assertFalse($strings->includes('consectetur'));

        $this->assertTrue($removed->includes('lorem'));
        $this->assertTrue($removed->includes('ipsum'));
        $this->assertTrue($removed->includes('sit'));
        $this->assertTrue($removed->includes('amet'));
        $this->assertFalse($removed->includes('dolor'));
        $this->assertFalse($strings->includes('consectetur'));

        $removed2 = $strings->remove('xxx');
        $this->assertNotSame($strings, $removed2);
        $this->assertCount(5, $removed2);
    }

    public function testStringCollection_map(): void
    {
        $strings = new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $upperCase = $strings->map('mb_strtoupper');

        $this->assertNotSame($strings, $upperCase);
        $this->assertCount(5, $strings);
        $this->assertCount(5, $upperCase);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertTrue($strings->includes('dolor'));
        $this->assertTrue($strings->includes('sit'));
        $this->assertTrue($strings->includes('amet'));
        $this->assertFalse($strings->includes('consectetur'));

        $this->assertTrue($upperCase->includes('LOREM'));
        $this->assertTrue($upperCase->includes('IPSUM'));
        $this->assertTrue($upperCase->includes('DOLOR'));
        $this->assertTrue($upperCase->includes('SIT'));
        $this->assertTrue($upperCase->includes('AMET'));
        $this->assertFalse($upperCase->includes('CONSECTETUR'));
    }

    public function testStringCollection_map_failure(): void
    {
        $strings = new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Callback should return a string');

        $strings->map(function () {
            return 1; // function does not return a string
        });
    }

    public function testStringCollection_filter(): void
    {
        $strings = new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $filtered = $strings->filter(function (string $word):bool {
            return \strpos($word, 'or') !== false;
        });

        $this->assertNotSame($strings, $filtered);
        $this->assertCount(5, $strings);
        $this->assertCount(2, $filtered);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertTrue($strings->includes('dolor'));
        $this->assertTrue($strings->includes('sit'));
        $this->assertTrue($strings->includes('amet'));
        $this->assertFalse($strings->includes('consectetur'));

        $this->assertTrue($filtered->includes('lorem'));
        $this->assertFalse($filtered->includes('ipsum'));
        $this->assertTrue($filtered->includes('dolor'));
        $this->assertFalse($filtered->includes('sit'));
        $this->assertFalse($filtered->includes('amet'));
    }

    public function testStringCollection_filter_failure(): void
    {
        $strings = new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Callback should return a boolean');

        $strings->filter(function () {
            return 1; // function does not return a boolean
        });
    }

    public function testStringCollection_filterNotEmpty(): void
    {
        $strings = new StringCollection('lorem', '', 'ipsum', '', 'dolor', ' ', 'sit', 'amet');
        $filtered = $strings->filterNotEmpty();

        $this->assertNotSame($strings, $filtered);
        $this->assertCount(8, $strings);
        $this->assertCount(6, $filtered);
    }

    public function testStringCollection_unique(): void
    {
        $strings = new StringCollection('lorem', '', 'ipsum', '', 'dolor', 'sit', 'amet', 'sit', 'lorem');
        $unique = $strings->unique();

        $this->assertNotSame($strings, $unique);
        $this->assertCount(9, $strings);
        $this->assertCount(6, $unique);
    }

    public function testStringCollection_duplicates(): void
    {
        $strings = new StringCollection('lorem', '', 'ipsum', '', 'dolor', 'sit', 'amet', 'sit', 'lorem');
        $duplicates = $strings->duplicates();

        $this->assertNotSame($strings, $duplicates);
        $this->assertCount(9, $strings);
        $this->assertCount(3, $duplicates);
        $this->assertTrue($duplicates->includes(''));
        $this->assertTrue($duplicates->includes('lorem'));
        $this->assertTrue($duplicates->includes('sit'));
    }
}