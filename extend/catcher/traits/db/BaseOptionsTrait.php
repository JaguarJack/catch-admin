<?php

namespace catcher\traits\db;

use catcher\CatchModelCollection;
use catcher\Utils;
use think\Collection;

trait BaseOptionsTrait
{
    /**
     * 允许更新字段
     *
     * @return void
     */
    public function allow_field()
    {
        $fields =  $this->getFields();
        $allow_field = [];
        foreach ($fields as $field) {
            $item = explode('|', $field['comment']);
            if (isset($item[3]) && !is_empty($item[3])) {
                $allow_field[] =  $field['name'];
            }
        }
        return $allow_field;
    }
    /**
     * 查询CURD布局
     *
     * @time 2020年04月28日
     * @return mixed
     */
    public function getLayout()
    {
        $fields =  $this->getFields();
        $table = [];
        $form = [];
        $edit_form = [];
        $form_rules = [];
        $edit_rules = [];
        $topSearch = [];
        $items = [];
        // return  $fields;
        foreach ($fields as $field) {
            $item = explode('|', $field['comment']);
            $items[] = count($item);
            $type = 'text';
            if (strpos($field['type'], 'char') !== false || strpos($field['type'], 'text') !== false)
                $type = 'input';
            if (strpos($field['name'], '_time') !== false)
                $type = 'date';
            if (strpos($field['name'], '_img') !== false)
                $type = 'upload-image';
            if ($field['name'] == "id" || strpos($field['name'], '_at') !== false || strpos($field['type'], 'decimal') !== false)
                $type = 'text';
            if (strpos($field['name'], 'password_safety') !== false)
                continue;
            if (strpos($field['name'], 'status') !== false || strpos($field['name'], 'is_') !== false)
                $type = 'switch';
            if (count($item) >= 2) {
                if (isset($item[1]) && !is_empty($item[1])) {
                    $table[$field['name']] = [
                        'label' => $item[0],
                        'sortable' => true,
                        'type' => $type,
                    ];
                }
                if (isset($item[2]) && !is_empty($item[2])) {
                    $form[$field['name']] = [
                        'label' => $item[0],
                        'type' => $type,
                    ];
                    $form_rules[$field['name']] = [
                        'message' => $item[0],
                        'required' => $field['notnull'],
                    ];
                }
                if (isset($item[3]) && !is_empty($item[3])) {
                    $edit_form[$field['name']] = [
                        'label' => $item[0],
                        'type' => $type,
                    ];
                    $edit_rules[$field['name']] = [
                        'message' => $item[0],
                        'required' => $field['notnull'],
                    ];
                }
                if (isset($item[4]) && !is_empty($item[4])) {
                    $array = [
                        'text' =>    $item[0],
                        'type' => $item[4],
                        'value' =>    $field['name'],
                    ];
                    if ($item[4] == "select") {
                        $array['options'] = [
                            [
                                'text' => "否",
                                'type' => "danger",
                                'value' => 0,
                            ], [
                                'text' => "是",
                                'type' => "success",
                                'value' => 1,
                            ]
                        ];
                    }
                    $topSearch[] = $array;
                }
            }
        }
        $layout = [
            'tableDesc' => $table,
            'formDesc' => $form,
            'editDesc' => $edit_form,
            'formRules' => $form_rules,
            'editRules' => $edit_rules,
            'topButtons' => [],
            'rightButtons' => [],
            'topSearch' => $topSearch,
        ];
        return $layout;
    }
    /**
     * 查询列表
     *
     * @time 2020年04月28日
     * @return mixed
     */
    public function getList()
    {
        // 不分页
        if (property_exists($this, 'paginate') && $this->paginate === false) {
            return $this->catchSearch()
                ->field('*')
                ->catchOrder()
                ->creator()
                ->select();
        }

        // 分页列表
        return $this->catchSearch()
            ->field('*')
            ->catchOrder()
            ->creator()
            ->paginate();
    }

    /**
     *
     * @time 2019年12月03日
     * @param array $data
     * @return bool
     */
    public function storeBy(array $data)
    {
        if ($this->allowField($this->field)->save($data)) {
            return $this->{$this->getPk()};
        }

        return false;
    }

    /**
     * 用于循环插入
     *
     * @time 2020年04月21日
     * @param array $data
     * @return mixed
     */
    public function createBy(array $data)
    {
        $model = parent::create($data, $this->field, true);

        return $model->{$this->getPk()};
    }
    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @param $data
     * @param string $field
     * @return bool
     */
    public function updateBy($id, $data, $field = ''): bool
    {
        if (static::update($data, [$field ?: $this->getPk() => $id], $this->field)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @param array $field
     * @param bool $trash
     * @return mixed
     */
    public function findBy($id, array $field = ['*'], $trash = false)
    {
        if ($trash) {
            return static::onlyTrashed()->find($id);
        }


        return static::where($this->getPk(), $id)->field($field)->find();
    }

    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @param $force
     * @return mixed
     */
    public function deleteBy($id, $force = false)
    {
        return static::destroy(is_array($id) ? $id : Utils::stringToArrayBy($id), $force);
    }

    /**
     * 批量插入
     *
     * @time 2020年04月19日
     * @param array $data
     * @return mixed
     */
    public function insertAllBy(array $data)
    {
        $newData = [];
        foreach ($data as $item) {
            foreach ($item as $field => $value) {
                if (!in_array($field, $this->field)) {
                    unset($item[$field]);
                }

                if (in_array('created_at', $this->field)) {
                    $item['created_at'] = time();
                }

                if (in_array('updated_at', $this->field)) {
                    $item['updated_at'] = time();
                }
            }
            $newData[] = $item;
        }
        return $this->insertAll($newData);
    }

    /**
     * @time 2019年12月07日
     * @param $id
     * @return mixed
     */
    public function recover($id)
    {
        return static::onlyTrashed()->find($id)->restore();
    }

    /**
     * 获取删除字段
     *
     * @time 2020年01月13日
     * @return mixed
     */
    public function getDeleteAtField()
    {
        return $this->deleteTime;
    }

    /**
     * 别名
     *
     * @time 2020年01月13日
     * @param $field
     * @return string
     */
    public function aliasField($field): string
    {
        return sprintf('%s.%s', $this->getTable(), $field);
    }

    /**
     * 禁用/启用
     *
     * @time 2020年06月29日
     * @param $id
     * @param string $field
     * @return mixed
     */
    public function disOrEnable($id, $field = 'status')
    {
        $model = $this->findBy($id);

        $status = $model->{$field} == self::DISABLE ? self::ENABLE : self::DISABLE;

        $model->{$field} = $status;

        return $model->save();
    }

    /**
     * rewrite collection
     *
     * @time 2020年10月20日
     * @param array|iterable $collection
     * @param string|null $resultSetType
     * @return CatchModelCollection|mixed
     */
    public function toCollection(iterable $collection = [], string $resultSetType = null): Collection
    {
        $resultSetType = $resultSetType ?: $this->resultSetType;

        if ($resultSetType && false !== strpos($resultSetType, '\\')) {
            $collection = new $resultSetType($collection);
        } else {
            $collection = new CatchModelCollection($collection);
        }

        return $collection;
    }
}
