<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value;

use Miquido\DataStructure\HashMap\HashMapInterface;
use Miquido\DataStructure\Value\Collection\CollectionValueInterface;
use Miquido\DataStructure\Value\Scalar\ScalarValueInterface;

interface ValueInterface
{
    public function scalar(): ScalarValueInterface;
    public function collection(bool $castScalar = true): CollectionValueInterface;
    public function hashMap(): HashMapInterface;

    public function getRawValue();
}