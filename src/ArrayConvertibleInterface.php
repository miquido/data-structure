<?php

declare(strict_types=1);

namespace Miquido\DataStructure;

interface ArrayConvertibleInterface
{
    public function toArray(): array;
}