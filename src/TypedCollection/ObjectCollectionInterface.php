<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\HashMap\HashMapInterface;

interface ObjectCollectionInterface extends \Countable
{
    public function toMap(callable $keyProvider): HashMapInterface;
    public function getAll(): array;
}