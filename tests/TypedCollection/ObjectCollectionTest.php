<?php

declare(strict_types=1);

namespace Tests\Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\TypedCollection\ObjectCollection;
use PHPUnit\Framework\TestCase;

class UserMock
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

final class ObjectCollectionTest extends TestCase
{
    public function testFromIterable(): void
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();
        $obj3 = new \stdClass();
        $generate = function () use ($obj1, $obj2, $obj3): iterable {
            yield $obj1;
            yield $obj2;
            yield $obj3;
        };

        $collection = ObjectCollection::fromIterator($generate());
        $this->assertCount(3, $collection);
        $this->assertSame([$obj1, $obj2, $obj3], $collection->getAll());
    }

    public function testToMap(): void
    {
        $email1 = '1@example.com';
        $user1 = new UserMock(1, $email1);
        $email2 = '2@example.com';
        $user2 = new UserMock(2, $email2);
        $collection = new ObjectCollection($user1, $user2);

        $map = $collection->toMap(function (UserMock $user) {
            return $user->getEmail();
        });

        $this->assertCount(2, $map);
        $this->assertSame([$email1, $email2], $map->keys()->values());
        $this->assertSame([$user1, $user2], $map->values());
    }

    public function testToMap_InvalidReturnedType(): void
    {
        $collection = new ObjectCollection(new UserMock(1, '1@example.com'), new UserMock(2, '2@example.com'));

        $this->expectException(\RuntimeException::class);
        $collection->toMap(function (UserMock $user) {
            return $user->getId();
        });
    }

    public function testToMap_DuplicatedKey(): void
    {
        $collection = new ObjectCollection(new UserMock(1, 'same@example.com'), new UserMock(2, 'same@example.com'));

        $this->expectException(\LogicException::class);
        $collection->toMap(function (UserMock $user) {
            return $user->getEmail();
        });
    }
}
