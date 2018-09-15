<?php

declare(strict_types=1);

namespace Miquido\DataStructure\Value\Scalar\Number;

use Miquido\DataStructure\ScalarConvertibleInterface;

interface NumberValueInterface extends ScalarConvertibleInterface
{
    public function map(callable $callback): NumberValueInterface;

    /**
     * @return int|float
     */
    public function get();

    public function int(): int;

    public function float(): float;
}
