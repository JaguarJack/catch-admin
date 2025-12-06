<?php

namespace Modules\Common\Repository\Options;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * mysql 表字段
 *
 * @class TableFields
 */
class TableFields implements OptionInterface
{
    public function get(): array|Collection
    {
        $data = [];
        $tables = get_all_tables();
        foreach ($tables as $table) {
            $columns = Schema::getColumnListing($table['name']);

            $data[] = [
                'name' => $table['name'],
                'columns' => $columns,
                'comment' => $table['comment'],
            ];
        }

        return $data;
    }
}
