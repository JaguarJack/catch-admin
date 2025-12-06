<?php

namespace Modules\Common\Repository\Options;

use Catch\Base\CatchModel;
use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\DateformatTrait;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\WithAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

/**
 * mysql 表数据，提供给前端的数据，格式 [{label:'label',value:'value'}]
 *
 * 如果有 parent_id 则为树形
 *
 * value 默认为 id，label 默认为 name
 *
 * @class TableData
 */
class TableData implements OptionInterface
{
    public function get(): array|Collection
    {
        // TODO: Implement get() method.
        $tableName = Request::get('table');
        if (! $tableName) {
            return [];
        }
        // value 字段
        $value = Request::get('value', 'id');
        // label 字段
        $label = Request::get('label', 'name');
        // 父级字段
        $parentIdColumn = Request::get('pid');
        // 过滤，只过滤 label 数据
        $filter = Request::get('filter');
        // 获取表字段
        $columns = Schema::getColumnListing($tableName);

        if (in_array('deleted_at', $columns)) {
            $model = new class extends CatchModel {};
        } else {
            $model = new class extends Model
            {
                use BaseOperate;
                use DateformatTrait;
                use ScopeTrait;
                use WithAttributes;
            };
        }

        $fields = [$value, $label];
        if ($parentIdColumn && in_array($parentIdColumn, $columns)) {
            $fields[] = $parentIdColumn;

            return $model->setTable($tableName)->get($fields)->toTree(pidField: $parentIdColumn)->values();
        } else {
            if (Request::get('page')) {
                $paginate = $model->setTable($tableName)->when($filter, fn ($query) => $query->whereLike($label, "{$filter}"))->select($fields)
                    ->paginate(Request::get('limit', 10));

                return [
                    'data' => $paginate->items(),
                    'total' => $paginate->total(),
                    'limit' => $paginate->perPage(),
                    'page' => $paginate->currentPage(),
                ];
            }

            return $model->setTable($tableName)->when($filter, fn ($query) => $query->whereLike($label, "{$filter}"))->get($fields);
        }
    }
}
