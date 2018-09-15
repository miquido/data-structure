<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface;
use Miquido\DataStructure\Value\Scalar\String\StringValueInterface;

interface ScalarValueInterface extends ScalarConvertibleInterface
{
    public function string(): string;
    public function int(): int;
    public function float(): float;
    public function bool(bool $parseString = true): bool;
    public function dateTime(): \DateTime;

    public function toStringValue(): StringValueInterface;
    public function toNumberValue(): NumberValueInterface;

    public function getRawValue();
}