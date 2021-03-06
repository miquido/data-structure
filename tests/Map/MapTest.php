<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests\Map;

use Miquido\DataStructure\ArrayConvertibleInterface;
use Miquido\DataStructure\Map\Map;
use Miquido\DataStructure\TypedCollection\IntegerCollection;
use Miquido\DataStructure\Value\Scalar\ScalarValue;
use PHPUnit\Framework\TestCase;

final class MapTest extends TestCase
{
    public function testCreation(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertCount(2, $map);
        $this->assertTrue($map->has('name'));
        $this->assertTrue($map->has('surname'));
        $this->assertSame('John', $map->get('name'));
        $this->assertSame('Smith', $map->get('surname'));
        $this->assertFalse($map->has('age'));
        $this->assertSame(['name' => 'John', 'surname' => 'Smith'], $map->toArray());
        $this->assertSame(['name', 'surname'], $map->keys()->values());
        $this->assertSame(['John', 'Smith'], $map->values());
    }

    public function testCreateWithNull(): void
    {
        $map = new Map();

        $this->assertCount(0, $map);
    }

    public function testCreateWithArrayConvertible(): void
    {
        $map = new Map(new class() implements ArrayConvertibleInterface {
            public function toArray(): array
            {
                return ['key' => 'value'];
            }
        });

        $this->assertCount(1, $map);
        $this->assertTrue($map->has('key'));
        $this->assertSame('value', $map->get('key'));
    }

    public function testGetValue(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $value = $map->getValue('name');
        $this->assertSame('John', $value->getRawValue());
    }

    public function testGet(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->expectException(\OutOfBoundsException::class);
        $map->get('age');
    }

    public function testGet_withDefaultValue(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertSame(40, $map->get('age', 40));
        $this->assertSame(40, $map->get('age', 40, true));
    }

    public function testGet_withDefaultNull(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertNull($map->get('age', null, true));

        $this->expectException(\OutOfBoundsException::class);
        $this->assertNull($map->get('age', null, false));
    }

    public function testSet(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);
        $withAge = $map->set('age', 40);

        $this->assertCount(2, $map);
        $this->assertCount(3, $withAge);

        $this->assertSame('John', $withAge->get('name'));
        $this->assertSame('Smith', $withAge->get('surname'));
        $this->assertSame(40, $withAge->get('age'));
    }

    public function testRemove(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40]);

        $onlyName = $map->remove('surname', 'age');
        $this->assertCount(3, $map);
        $this->assertCount(1, $onlyName);
        $this->assertTrue($onlyName->has('name'));
        $this->assertFalse($onlyName->has('surname'));
        $this->assertFalse($onlyName->has('age'));

        $notChanged = $map->remove('email');
        $this->assertNotSame($map, $notChanged);
        $this->assertCount(3, $map);
        $this->assertCount(3, $notChanged);
        $this->assertTrue($notChanged->has('name'));
        $this->assertTrue($notChanged->has('surname'));
        $this->assertTrue($notChanged->has('age'));
    }

    public function testPick(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40]);

        $picked = $map->pick('name', 'surname');

        $this->assertCount(3, $map);
        $this->assertCount(2, $picked);
        $this->assertTrue($picked->has('name'));
        $this->assertTrue($picked->has('surname'));
        $this->assertFalse($picked->has('age'));
    }

    public function testRename(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40]);

        $renamed = $map->rename('name', 'firstName')->rename('surname', 'lastName');

        $this->assertNotSame($map, $renamed);
        $this->assertCount(3, $map);
        $this->assertCount(3, $renamed);

        $this->assertTrue($map->has('name'));
        $this->assertTrue($map->has('surname'));
        $this->assertTrue($map->has('age'));
        $this->assertFalse($map->has('firstName'));
        $this->assertFalse($map->has('lastName'));

        $this->assertFalse($renamed->has('name'));
        $this->assertFalse($renamed->has('surname'));
        $this->assertTrue($renamed->has('age'));
        $this->assertTrue($renamed->has('firstName'));
        $this->assertTrue($renamed->has('lastName'));
        $this->assertSame($map->get('name'), $renamed->get('firstName'));
        $this->assertSame($map->get('surname'), $renamed->get('lastName'));
        $this->assertSame($map->get('age'), $renamed->get('age'));
    }

    public function testRenameShouldFailWhenKeyDoesNotExist(): void
    {
        $map = Map::create(['email' => 'john@smith.com']);

        $this->expectException(\InvalidArgumentException::class);
        $map->rename('name', 'firstName');
    }

    public function testRenameShouldFailWhenNewKeyAlreadyNotExist(): void
    {
        $map = Map::create(['name' => 'John', 'firstName' => 'John']);

        $this->expectException(\InvalidArgumentException::class);
        $map->rename('name', 'firstName');
    }

    public function testFilter(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40, 'email' => 'john@smith.com']);

        $filtered = $map->filter(function ($value, string $key): bool {
            return \is_string($value) && false !== \mb_strpos($key, 'name');
        });

        $this->assertCount(4, $map);
        $this->assertCount(2, $filtered);
        $this->assertTrue($filtered->has('name'));
        $this->assertTrue($filtered->has('surname'));
        $this->assertFalse($filtered->has('age'));
        $this->assertFalse($filtered->has('email'));
    }

    public function testFilterByValues(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40]);

        $filtered = $map->filterByValues(function ($value): bool {
            return \is_string($value);
        });

        $this->assertCount(3, $map);
        $this->assertCount(2, $filtered);
        $this->assertTrue($filtered->has('name'));
        $this->assertTrue($filtered->has('surname'));
        $this->assertFalse($filtered->has('age'));
    }

    public function testFilterByKey(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith', 'age' => 40, 'email' => 'john@smith.com']);

        $filtered = $map->filterByKeys(function (string $key): bool {
            return false !== \mb_strpos($key, 'name');
        });

        $this->assertCount(4, $map);
        $this->assertCount(2, $filtered);
        $this->assertTrue($filtered->has('name'));
        $this->assertTrue($filtered->has('surname'));
        $this->assertFalse($filtered->has('age'));
        $this->assertFalse($filtered->has('email'));
    }

    public function testMerge(): void
    {
        $map1 = new Map(['name' => 'John', 'surname' => 'Sm', 'age' => 40]);
        $map2 = new Map(['name' => 'John', 'surname' => 'Smith', 'email' => 'john@smith.com']);

        $map = $map1->merge($map2);

        $this->assertCount(3, $map1);
        $this->assertCount(3, $map2);
        $this->assertCount(4, $map);
        $this->assertSame('Smith', $map->get('surname'));
    }

    public function testEquals(): void
    {
        $map1 = new Map(['name' => 'John', 'surname' => 'Smith']);
        $map2 = new Map(['name' => 'John', 'surname' => 'Smith', 'age' => 40]);
        $map3 = new Map(['name' => 'John', 'surname' => 'Smiths', 'age' => 40]);
        $map4 = new Map(['name' => 'John', 'surname' => 'Smith']);

        $this->assertTrue($map1->equals($map1));
        $this->assertFalse($map1->equals($map2));
        $this->assertFalse($map1->equals($map3));
        $this->assertTrue($map1->equals($map4));
    }

    public function testMapKeys(): void
    {
        $map = new Map(['name' => 'John', 'surname' => 'Smith']);

        $mappedKeys = $map->mapKeys(function (string $key): string {
            return \mb_strtoupper($key);
        });

        $this->assertNotSame($map, $mappedKeys);
        $this->assertTrue($map->has('name'));
        $this->assertTrue($map->has('surname'));
        $this->assertFalse($map->has('NAME'));
        $this->assertFalse($map->has('SURNAME'));
        $this->assertFalse($mappedKeys->has('name'));
        $this->assertFalse($mappedKeys->has('surname'));
        $this->assertTrue($mappedKeys->has('NAME'));
        $this->assertTrue($mappedKeys->has('SURNAME'));
    }

    public function testMapKeysShouldFailWhenDuplicatedKey(): void
    {
        $map = new Map(['name' => 'John', 'surname' => 'Smith']);

        $this->expectException(\LogicException::class);
        $map->mapKeys(function (): string {
            return 'name';
        });
    }

    public function testHasOneOf(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertTrue($map->hasOneOf('name'));
        $this->assertTrue($map->hasOneOf('name', 'surname'));
        $this->assertTrue($map->hasOneOf('name', 'surname', 'age'));
        $this->assertTrue($map->hasOneOf('name', 'age'));
        $this->assertFalse($map->hasOneOf('age'));
        $this->assertFalse($map->hasOneOf('age', 'email'));
    }

    public function testHasAll(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertTrue($map->hasAll('name'));
        $this->assertTrue($map->hasAll('name', 'surname'));
        $this->assertFalse($map->hasAll('name', 'surname', 'age'));
        $this->assertFalse($map->hasAll('name', 'age'));
        $this->assertFalse($map->hasAll('age'));
        $this->assertFalse($map->hasAll('age', 'email'));
    }

    public function testArrayAccess(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->assertArrayHasKey('name', $map);
        $this->assertArrayHasKey('surname', $map);
        $this->assertArrayNotHasKey('age', $map);

        $this->assertSame('John', $map['name']);
        $this->assertSame('Smith', $map['surname']);
    }

    public function testArrayAccess_offsetSetShouldFail(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->expectException(\LogicException::class);
        $map['age'] = 40;
    }

    public function testArrayAccess_offsetUnsetShouldFail(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $this->expectException(\LogicException::class);
        unset($map['name']);
    }

    public function testIteratorWorks(): void
    {
        $map = Map::create(['name' => 'John', 'surname' => 'Smith']);

        $iterations = 0;
        foreach ($map as $key => $value) {
            ++$iterations;
            $this->assertContains($key, ['name', 'surname']);
            $this->assertContains($value, ['John', 'Smith']);
        }

        $this->assertEquals(2, $iterations);
    }

    public function testToArray_nestedArray(): void
    {
        $map = new Map([
            'number' => 123,
            'string' => 'some string',
            'integers' => new IntegerCollection(1, 2, 3),
            'scalar' => new ScalarValue('a string'),
        ]);

        $this->assertSame([
            'number' => 123,
            'string' => 'some string',
            'integers' => [1, 2, 3],
            'scalar' => 'a string',
        ], $map->toArray());
    }
}
