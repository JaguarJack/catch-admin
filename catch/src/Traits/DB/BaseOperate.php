<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch\Traits\DB;

use Catch\Enums\Status;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

/**
 * base operate
 */
trait BaseOperate
{
    protected string $sortField = 'sort';

    protected bool $sortDesc = true;


    /**
     * @var string
     */
    protected string $parentId = 'parent_id';

    /**
     *
     *
     * @return void
     */
    public function initializeBaseOperate(): void
    {
        if (property_exists($this, 'mergeCasts')) {
            $this->mergeCasts($this->mergeCasts);
        }

        if (property_exists($this, 'mergeHidden')) {
            $this->makeHidden($this->mergeHidden);
        }
    }

    /**
     * @return mixed
     */
    public function getList(): mixed
    {
        $queryBuilder = self::query()->select($this->fieldsInList)->quickSearch();

        if (in_array($this->sortField, $this->getFillable())) {
            $queryBuilder = $queryBuilder->orderBy($this->sortField, $this->sortDesc ? 'desc' : 'asc');
        }

        $queryBuilder = $queryBuilder->orderByDesc('id');

        if ($this->isPaginate) {
            return $queryBuilder->paginate(Request::get('limit', $this->perPage));
        }



        return $queryBuilder->get();
    }

    /**
     * save
     *
     * @param array $data
     * @return false|mixed
     */
    public function storeBy(array $data): mixed
    {
        if ($this->fill($this->filterData($data))->save()) {
            return $this->getKey();
        }

        return false;
    }

    /**
     * create
     *
     * @param array $data
     * @return false|mixed
     */
    public function createBy(array $data): mixed
    {
        $model = $this->newInstance();

        if ($model->fill($this->filterData($data))->save()) {
            return $model->getKey();
        }

        return false;
    }

    /**
     * update
     *
     * @param $id
     * @param array  $data
     * @return mixed
     */
    public function updateBy($id, array $data): mixed
    {
        return $this->where($this->getKeyName(), $id)->update($this->filterData($data));
    }

    /**
     * filter data/ remove null && empty string
     *
     * @param array $data
     * @return array
     */
    protected function filterData(array $data): array
    {
        // 表单保存的数据集合
        $form = property_exists($this, 'form') ? $this->form : [];

        foreach ($data as $k => $val) {
            if (is_null($val) || (is_string($val) && ! $val)) {
                unset($data[$k]);
            }

            if (! empty($form) && ! in_array($k, $form)) {
                unset($data[$k]);
            }
        }

        return $data;
    }


    /**
     * get first by ID
     *
     * @param $id
     * @param string[] $columns
     * @return ?Model
     */
    public function firstBy($id, array $columns = ['*']): ?Model
    {
        return static::where($this->getKeyName(), $id)->first($columns);
    }

    /**
     * delete model
     *
     * @param $id
     * @param bool $force
     * @return bool|null
     */
    public function deleteBy($id, bool $force = false): ?bool
    {
        /* @var Model $model */
        $model = self::find($id);

        if ($force) {
            return $model->forceDelete();
        }

        return $model->delete();
    }

    /**
     * disable or enable
     *
     * @param $id
     * @param string $field
     * @return bool
     */
    public function disOrEnable($id, string $field = 'status'): bool
    {
        $model = self::firstBy($id);

        $model->{$field} = $model->{$field} == Status::Enable->value() ? Status::Disable->value() : Status::Enable->value();

        if ($model->save() && in_array($this->parentId, $this->getFillable())) {
            $this->updateChildren($id, $field, $model->{$field});
        }

        return true;
    }


    /**
     * 递归处理
     *
     * @param int|array $parentId
     * @param string $field
     * @param int $value
     */
    public function updateChildren(mixed $parentId, string $field, mixed $value): void
    {
        if (! $parentId instanceof Arrayable) {
            $parentId = Collection::make([$parentId]);
        }

        $childrenId = $this->whereIn('parent_id', $parentId)->pluck('id');

        if ($childrenId->count()) {
            if ($this->whereIn('parent_id', $parentId)->update([
                $field => $value
            ])) {
                $this->updateChildren($childrenId, $field, $value);
            }
        }
    }

    /**
     * alias field
     *
     * @param string|array $fields
     * @return string|array
     */
    public function aliasField(string|array $fields): string|array
    {
        $table = $this->getTable();

        if (is_string($fields)) {
            return "{$table}.{$fields}";
        }

        foreach ($fields as &$field) {
            $field = "{$table}.{$field}";
        }

        return $fields;
    }


    /**
     * get updated at column
     *
     * @return string|null
     */
    public function getUpdatedAtColumn(): ?string
    {
        $updatedAtColumn = parent::getUpdatedAtColumn();

        if (! in_array(parent::getUpdatedAtColumn(), $this->getFillable())) {
            $updatedAtColumn = null;
        }

        return $updatedAtColumn;
    }

    /**
     * get created at column
     *
     * @return string|null
     */
    public function getCreatedAtColumn(): ?string
    {
        $createdAtColumn = parent::getCreatedAtColumn();

        if (! in_array(parent::getUpdatedAtColumn(), $this->getFillable())) {
            $createdAtColumn = null;
        }

        return $createdAtColumn;
    }

    /**
     * whit form data
     *
     * @return $this
     */
    public function withoutForm(): static
    {
        if (property_exists($this, 'form') && ! empty($this->form)) {
            $this->form = [];
        }

        return $this;
    }
}
