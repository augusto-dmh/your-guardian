<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class EnumHelper
{
    public static function getEnumValues(string $table, string $column): array
    {
        $type = DB::select(
            "SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'"
        )[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = str_getcsv($matches[1], ',', "'");

        return $enum;
    }
}
