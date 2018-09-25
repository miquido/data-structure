<?php

declare(strict_types=1);

namespace Miquido\DataStructure\TypedCollection;

use Miquido\DataStructure\Map\MapInterface;

interface ObjectCollectionInterface extends \Countable
{
    public function toMap(callable $keyProvider = null): MapInterface;

    public function getAll(): array;
}
