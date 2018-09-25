<?php

declare(strict_types=1);

namespace Miquido\DataStructure;

final class ArrayConverter
{
    public static function toArray(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value instanceof ArrayConvertibleInterface) {
                $value = $value->toArray();
            } elseif ($value instanceof ScalarConvertibleInterface) {
                $value = $value->toScalar();
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
