<?php
namespace Modules\Permissions\Models\Traits;

use Catch\Facade\Admin;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * 数据库字段访问权限
 */
trait ColumnAccess
{
    /**
     * @param array $columns
     * @return array
     */
    public function readable(array $columns): array
    {
        return $this->filter($columns);
    }

    /**
     * @param array $columns
     * @return array
     */
    public function writable(array $columns): array
    {
        return $this->filter($columns, false);
    }

    /**
     * 获取字段管理的角色集合
     *
     * @return mixed
     */
    protected function getTableColumnRoles(): mixed
    {
        return Cache::get('column_has_roles', []);
    }

    /**
     * filter 过滤
     *
     * @param array $columns
     * @param bool $isReadable
     * @return array
     * @throws AuthenticationException
     */
    protected function filter(array $columns, bool $isReadable = true): array
    {
        /* @var User $user */
        $user = Admin::currentLoginUser();

        // 如果是超级管理员，直接返回
        if ($user->isSuperAdmin()) {
            return $columns;
        }

        $tableColumnRoles = $this->getTableColumnRoles();

        // 如果缓存没有数据，直接返回
        if (empty($tableColumnRoles)) {
            return $columns;
        }

        $columnHasRoles = $tableColumnRoles[withTablePrefix($this->getTable())] ?? [];

        if (empty($columnHasRoles)) {
            return $columns;
        }

        $currentUserHasRoles = $user->roles->pluck('id')->toArray();

        // 处理 * 字段
        $columns = $this->dealWithColumns($columns);

        foreach ($columns as $k => $column) {
            // 解析出表的原始字段名称
            $parseColumn = $this->parseColumn($column);
            // 获取字段的权限的角色集合
            $roles = $columnHasRoles[$parseColumn][$isReadable ? 'readable_roles' : 'writeable_roles'] ?? [];
            if (!empty($roles)) {
                // 如果当前用户没有角色，没有权限访问该字段
                if (empty($currentUserHasRoles)) {
                    unset($columns[$k]);
                }
                // 求交集
                if (empty(array_intersect($roles, $currentUserHasRoles))) {
                    unset($columns[$k]);
                }
            }
        }

        return $columns;
    }

    /**
     * 处理表字段栏目
     *
     * @param array $columns
     * @return array
     */
    protected function dealWithColumns(array $columns): array
    {
        $newColumns = [];
        $currentModelTableName = $this->getTable();
        foreach ($columns as $column) {
            $strColumn = Str::of($column);
            if ($strColumn->contains('*')) {
                if ($strColumn->contains('.')) {
                    [$tableName, $column] = explode('.', $column);
                    $newColumns = array_merge($newColumns, $this->combinateColumn($tableName));
                } else {
                    $newColumns = array_merge($newColumns, $this->combinateColumn($currentModelTableName));
                }
            } else {
                $newColumns[] = $column;
            }
        }

        return $newColumns;
    }

    /**
     * @param $tableName
     * @return array
     */
    protected function combinateColumn($tableName): array
    {
        $columns = [];
        $fillable = $this->getFillable();
        // 如果是当前模型的表名，直接获取 fillable
        if ($this->getTable() == $tableName) {
            foreach ($fillable as $value) {
                $columns[] = $tableName . '.' . $value;
            }
        } else {
            // 如果不是 动态获取表的字段
            foreach (Schema::getColumnListing($tableName) as $value) {
                $columns[] = $tableName . '.' . $value;
            }
        }

        return $columns;
    }

    /**
     * 解析原始 column
     *
     * @param string $column
     * @return string
     */
    protected function parseColumn(string $column): string
    {
        /**
         * 这里解析几种格式的 column
         * 单独的 column
         * 别名的 column (例如: user_name as username)
         * 连表的 column (例如: user.name)
         * 连表别名的 column (例如: user.name as username)
         * 其他的请显性操作
         */

        $dot = '.';
        $as = ' as ';

        $column = Str::of($column);
        $isContainsDot = $column->contains($dot);
        $isContainsAs = $column->contains($as);

        // 返回原始 column
        if (!$isContainsAs && !$isContainsDot) {
            return $column->toString();
        }
        // 只包含 .
        if ($isContainsDot && !$isContainsAs) {
            return $column->explode('.')->last();
        }

        // 包含 as
        if ($isContainsAs && !$isContainsDot) {
            return $column->explode($as)->first();
        }

        // 包含 . 和 as
        if ($isContainsAs && $isContainsDot) {
            return Str::of($column->explode($as)->first())->explode($dot)->last();
        }

        // 其他
        return $column;
    }
}
