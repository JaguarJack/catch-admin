<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\System\Models\RoleHasColumns;
use Modules\System\Models\TableColumn;
use mysql_xdevapi\Schema;

/**
 * @property $role_name
 * @property $identify
 * @property $parent_id
 * @property $description
 * @property $data_range
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = ['id', 'role_name', 'identify', 'parent_id', 'description', 'data_range', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'role_name', 'identify', 'parent_id', 'description', 'data_range', 'created_at', 'updated_at'];

    protected array $form = ['role_name', 'identify', 'parent_id', 'description', 'data_range'];

    protected array $formRelations = ['permissions', 'departments'];

    protected bool $isPaginate = false;

    public array $searchable = [
        'role_name' => 'like',

        'id' => '<>',
    ];

    protected bool $asTree = true;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    /**
     * departments
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Departments::class, 'role_has_departments', 'role_id', 'department_id');
    }

    // 可读字段
    public function readableColumns(): BelongsToMany
    {
        return $this->belongsToMany(
            TableColumn::class,
            RoleHasColumns::class,
            'role_id',
            'table_column_id',
        )->wherePivot('type', RoleHasColumns::READABLE); // 可读类型
    }

    // 可写字段
    public function writeableColumns(): BelongsToMany
    {
        return $this->belongsToMany(
            TableColumn::class,
            RoleHasColumns::class,
            'role_id',
            'table_column_id',
        )->wherePivot('type', RoleHasColumns::WRITABLE); // 可写类型
    }

    /**
     * 保存可读字段
     *
     * @param $id
     * @param $columnIds
     * @return array
     */
    public function saveReadableColumns($id, $saveColumnIds, $ColumnIds): array
    {
        return $this->find($id)
                    ->readableColumns()
                    ->wherePivotIn('table_column_id', $ColumnIds)
                    ->syncWithPivotValues($saveColumnIds, ['type' => RoleHasColumns::READABLE]);
    }

    /**
     * 移除可读字段
     *
     * @param $id
     * @param $columnIds
     * @return void
     */
    public function detachReadableColumns($id, $columnIds): void
    {
        $this->find($id)
                    ->readableColumns()
                    ->detach($columnIds);
    }

    /**
     * 保存可写字段
     *
     * @param $id
     * @param $saveColumnIds
     * @param $columnIds
     * @return array
     */
    public function saveWriteableColumns($id, $saveColumnIds, $columnIds): array
    {
        return $this->find($id)
            ->writeableColumns()
            ->wherePivotIn('table_column_id', $columnIds)
            ->syncWithPivotValues($saveColumnIds, ['type' => RoleHasColumns::WRITABLE]);
    }

    /**
     * 移除可写字段
     *
     * @param $id
     * @param $columnIds
     * @return void
     */
    public function detachWriteableColumns($id, $columnIds): void
    {
        $this->find($id)
            ->writeableColumns()
            ->detach($columnIds);
    }

    /**
     * get role's permissions
     */
    public function getPermissions(): Collection
    {
        return $this->permissions()->get();
    }

    /**
     * get role's departments
     */
    public function getDepartments(): Collection
    {
        return $this->departments()->get();
    }
}
