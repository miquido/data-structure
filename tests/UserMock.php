<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Tests;

final class UserMock
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
