<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar;

use Miquido\DataStructure\ScalarConvertibleInterface;
use Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface;
use Miquido\DataStructure\Value\Scalar\String\StringValueInterface;

interface ScalarValueInterface extends ScalarConvertibleInterface
{
    public function cast(string $type): ScalarValueInterface;
    public function bool(bool $parseString = true): bool;
    public function date(): \DateTime;

    public function string(): StringValueInterface;
    public function number(): NumberValueInterface;

    public function getRawValue();
}