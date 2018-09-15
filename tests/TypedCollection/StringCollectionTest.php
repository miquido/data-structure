<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests\TypedCollection;

use Miquido\DataStructure\TypedCollection\StringCollection;
use PHPUnit\Framework\TestCase;

final class StringCollectionTest extends TestCase
{
    public function testStaticCreation(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $this->assertCount(5, $strings);
        $this->assertSame(['lorem', 'ipsum', 'dolor', 'sit', 'amet'], $strings->toArray());
        $this->assertSame(['lorem', 'ipsum', 'dolor', 'sit', 'amet'], $strings->values());
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertTrue($strings->includes('dolor'));
        $this->assertTrue($strings->includes('sit'));
        $this->assertTrue($strings->includes('amet'));
    }

    public function testIteratorWorks(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum');
        $iterations = 0;
        foreach ($strings as $string) {
            ++$iterations;
            $this->assertContains($string, ['lorem', 'ipsum']);
        }
        $this->assertSame(2, $iterations);
    }

    public function testPush(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum');
        $new = $strings->push('dolor', 'sit');
        $this->assertCount(2, $strings);
        $this->assertCount(4, $new);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertFalse($strings->includes('dolor'));
        $this->assertFalse($strings->includes('sit'));
        $this->assertTrue($new->includes('lorem'));
        $this->assertTrue($new->includes('ipsum'));
        $this->assertTrue($new->includes('dolor'));
        $this->assertTrue($new->includes('sit'));
    }

    public function testRemove(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'dolor', 'sit');
        $new = $strings->remove('dolor', 'ipsum');
        $this->assertCount(4, $strings);
        $this->assertCount(2, $new);
        $this->assertTrue($strings->includes('lorem'));
        $this->assertTrue($strings->includes('ipsum'));
        $this->assertTrue($strings->includes('dolor'));
        $this->assertTrue($strings->includes('sit'));
        $this->assertTrue($new->includes('lorem'));
        $this->assertFalse($new->includes('ipsum'));
        $this->assertFalse($new->includes('dolor'));
        $this->assertTrue($new->includes('sit'));
    }

    public function testJoin(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'dolor', 'sit', 'amet');
        $string = $strings->join(',');
        $this->assertSame('lorem,ipsum,dolor,sit,amet', $string);
    }

    public function testDuplicates(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'sit', 'lorem', 'sit');
        $duplicates = $strings->duplicates();
        $this->assertCount(5, $strings);
        $this->assertCount(2, $duplicates);
        $this->assertContains('lorem', $duplicates);
        $this->assertContains('sit', $duplicates);
        $this->assertNotContains('ipsum', $duplicates);
    }

    public function testFilterNotIn(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'dolor', 'sit', 'amet', 'lorem');
        $filtered = $strings->filterNotIn('ipsum', 'sit');
        $this->assertCount(6, $strings);
        $this->assertCount(4, $filtered);
    }

    public function testFilterNotEmpty(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', '', 'sit', '', 'lorem');
        $filtered = $strings->filterNotEmpty();
        $this->assertCount(6, $strings);
        $this->assertCount(4, $filtered);
        $this->assertTrue($strings->includes(''));
        $this->assertFalse($filtered->includes(''));
    }

    public function testUnique(): void
    {
        $strings = StringCollection::create('lorem', 'ipsum', 'sit', 'lorem', 'sit');
        $unique = $strings->unique();
        $this->assertCount(5, $strings);
        $this->assertCount(3, $unique);
    }

    public function testMap_MultipleCallbacks(): void
    {
        $strings = StringCollection::create(' lorem ', ' ipsum ');
        $mapped = $strings->map('trim', 'strtoupper');
        $this->assertNotSame($strings, $mapped);
        $this->assertCount(2, $mapped);
        $this->assertContains('LOREM', $mapped);
        $this->assertContains('IPSUM', $mapped);
        $this->assertContains(' lorem ', $strings);
        $this->assertContains(' ipsum ', $strings);
        $this->assertNotContains(' lorem ', $mapped);
        $this->assertNotContains(' ipsum ', $mapped);
    }

    public function testTrimAll(): void
    {
        $strings = StringCollection::create(' lorem ', ' ipsum ');
        $trimmed = $strings->trimAll();
        $this->assertNotSame($strings, $trimmed);
        $this->assertCount(2, $trimmed);
        $this->assertContains('lorem', $trimmed);
        $this->assertContains('ipsum', $trimmed);
        $this->assertContains(' lorem ', $strings);
        $this->assertContains(' ipsum ', $strings);
        $this->assertNotContains(' lorem ', $trimmed);
        $this->assertNotContains(' ipsum ', $trimmed);
    }

    public function testToUpperCaseAll(): void
    {
        $strings = StringCollection::create(' lorem ', ' ipsum ');
        $upperCased = $strings->toUpperCaseAll();
        $this->assertNotSame($strings, $upperCased);
        $this->assertCount(2, $upperCased);
        $this->assertContains(' LOREM ', $upperCased);
        $this->assertContains(' IPSUM ', $upperCased);
        $this->assertContains(' lorem ', $strings);
        $this->assertContains(' ipsum ', $strings);
        $this->assertNotContains(' lorem ', $upperCased);
        $this->assertNotContains(' ipsum ', $upperCased);
    }

    public function testToLowerCaseAll(): void
    {
        $strings = StringCollection::create(' LOREM ', ' IPSUM ');
        $lowerCased = $strings->toLowerCaseAll();
        $this->assertNotSame($strings, $lowerCased);
        $this->assertCount(2, $lowerCased);
        $this->assertContains(' lorem ', $lowerCased);
        $this->assertContains(' ipsum ', $lowerCased);
        $this->assertContains(' LOREM ', $strings);
        $this->assertContains(' IPSUM ', $strings);
        $this->assertNotContains(' LOREM ', $lowerCased);
        $this->assertNotContains(' IPSUM ', $lowerCased);
    }
}
