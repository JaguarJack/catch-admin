<?php

namespace Modules\Common\Repository\Options;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Schemas implements OptionInterface
{
    public function get(): array
    {
        $options = [];
        $connection = DB::connection();
        $databaseName = $connection->getDatabaseName();
        $tablePrefix = $connection->getTablePrefix();

        $tables = Schema::getTables(is_pgsql(DB::getDefaultConnection()) ? '' : $databaseName);

        foreach ($tables as $table) {
            $tableName = Str::of($table['name'])->replaceStart($tablePrefix, '');

            $options[] = [
                'label' => $tableName . "\t\t\t\t" . $table['comment'],
                'value' => $tableName,
            ];
        }

        return $options;
    }
}
