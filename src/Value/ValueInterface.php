<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value;

use Miquido\DataStructure\Map\MapInterface;
use Miquido\DataStructure\Value\Collection\CollectionValueInterface;
use Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface;
use Miquido\DataStructure\Value\Scalar\ScalarValueInterface;
use Miquido\DataStructure\Value\Scalar\String\StringValueInterface;

interface ValueInterface
{
    public function toMap(): MapInterface;

    public function toCollectionValue(bool $castScalar = true): CollectionValueInterface;

    public function toStringValue(): StringValueInterface;

    public function toNumberValue(): NumberValueInterface;

    public function toScalarValue(): ScalarValueInterface;

    public function string(): string;

    public function int(): int;

    public function float(): float;

    public function bool(bool $parseString = true): bool;

    public function dateTime(): \DateTime;

    public function getRawValue();
}
