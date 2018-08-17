<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\HashMap;

use Miquido\DataStructure\HashMap\HashMap;
use PHPUnit\Framework\TestCase;

final class HashMapTest extends TestCase
{
    public function testHashMap(): void
    {
        $empty = new HashMap();

        $map = $empty->set('id', 1)->set('name', 'John Smith')->set('email', 'john@smith.com');

        $this->assertNotSame($empty, $map);
        $this->assertFalse($empty->equals($map));
        $this->assertFalse($map->equals($empty));

        $this->assertCount(0, $empty);
        $this->assertFalse($empty->has('id'));
        $this->assertFalse($empty->has('name'));
        $this->assertFalse($empty->has('email'));

        $this->assertCount(3, $map);
        $this->assertTrue($map->has('id'));
        $this->assertTrue($map->has('name'));
        $this->assertTrue($map->has('email'));
        $this->assertTrue($map->hasOneOf('id', 'email', 'role'));
        $this->assertTrue($map->hasOneOf('name', 'email', 'role'));
        $this->assertTrue($map->hasOneOf('id', 'email', 'role'));
        $this->assertTrue($map->hasOneOf('id', 'name'));
        $this->assertTrue($map->hasAll('id', 'name'));
        $this->assertFalse($map->has('role'));
        $this->assertFalse($map->hasAll('id', 'name', 'email', 'role'));
        $this->assertEquals(1, $map->get('id'));
        $this->assertEquals('John Smith', $map->get('name'));
        $this->assertEquals('john@smith.com', $map->get('email'));

        $keys = $map->keys();
        $this->assertCount(3, $keys);
        $this->assertTrue($keys->includes('id'));
        $this->assertTrue($keys->includes('name'));
        $this->assertTrue($keys->includes('email'));
        $this->assertFalse($keys->includes('role'));

        $values = $map->values();
        $this->assertCount(3, $values);
        $this->assertContains(1, $values);
        $this->assertContains('John Smith', $values);
        $this->assertContains('john@smith.com', $values);


        $newName = $map->set('name', 'John');
        $this->assertNotSame($map, $newName);
        $this->assertFalse($newName->equals($map));
        $this->assertFalse($map->equals($newName));
        $this->assertCount(3, $newName);
        $this->assertCount(3, $map);
        $this->assertTrue($newName->has('name'));
        $this->assertEquals('John', $newName->get('name'));
        $this->assertEquals('John Smith', $map->get('name'));

        $withoutEmail = $map->remove('email');
        $this->assertNotSame($map, $withoutEmail);
        $this->assertFalse($map->equals($withoutEmail));
        $this->assertFalse($withoutEmail->equals($map));
        $this->assertCount(2, $withoutEmail);
        $this->assertCount(3, $map);
        $this->assertTrue($withoutEmail->has('id'));
        $this->assertTrue($withoutEmail->has('name'));
        $this->assertFalse($withoutEmail->has('email'));

        $renamed = $map->rename('name', 'surname');
        $this->assertNotSame($map, $renamed);
        $this->assertFalse($map->equals($renamed));
        $this->assertFalse($map->equals($renamed));
        $this->assertCount(3, $renamed);
        $this->assertCount(3, $map);
        $this->assertTrue($map->has('name'));
        $this->assertFalse($renamed->has('name'));
        $this->assertTrue($renamed->has('surname'));

        $picked = $map->pick('id', 'name');
        $this->assertNotSame($map, $renamed);
        $this->assertFalse($map->equals($picked));
        $this->assertFalse($picked->equals($map));
        $this->assertCount(2, $picked);
        $this->assertCount(3, $map);
        $this->assertTrue($picked->has('id'));
        $this->assertTrue($picked->has('name'));
        $this->assertFalse($picked->has('email'));

        $newMap = new HashMap([
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'john@smith.com',
        ]);

        $this->assertTrue($map->equals($newMap));
        $this->assertEquals($map->get('id'), $newMap->get('id'));
        $this->assertEquals($map->get('name'), $newMap->get('name'));
        $this->assertEquals($map->get('email'), $newMap->get('email'));
    }
}