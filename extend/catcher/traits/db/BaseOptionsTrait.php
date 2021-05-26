<?php
declare(strict_types=1);

namespace catcher\traits\db;

use catcher\library\excel\reader\Reader;
use catcher\Utils;

trait BaseOptionsTrait
{
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
        if ($this->allowField($this->field)->save($this->filterData($data))) {
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
        $model = static::create($data, $this->field, true);

        return $model->{$this->getPk()};
    }

    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @param $data
     * @param string $field
     * @return bool
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function updateBy($id, $data, string $field = ''): bool
    {
        if (static::update($this->filterData($data), [$field ? : $this->getPk() => $id], $this->field)) {

            $this->updateChildren($id, $data);

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
    public function findBy($id, array $field = ['*'], bool $trash = false)
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
     * @param bool $force
     * @return mixed
     */
    public function deleteBy($id, bool $force = false)
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

                if (in_array($this->createTime, $this->field)) {
                    $item[$this->createTime] = time();
                }

                if (in_array($this->updateTime, $this->field)) {
                    $item[$this->updateTime] = time();
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
     * 更新下级
     *
     * @time 2021年04月28日
     * @param $parentId
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return void
     */
    protected function updateChildren($parentId, $data)
    {
        if (property_exists($this, 'updateChildrenFields')) {
            $parentIdField = property_exists($this, 'parentId') ? $this->$parentId : 'parent_id';

            if (!empty($this->updateChildrenFields)) {
                if (is_array($this->updateChildrenFields)) {
                    foreach ($data as $field => $value) {
                        if (! in_array($field, $this->updateChildrenFields)) {
                            unset($data[$field]);
                        }
                    }

                    $this->recursiveUpdate($parentId, $parentIdField, $data);
                }

                if (is_string($this->updateChildrenFields) && isset($data[$this->updateChildrenFields])) {
                    $this->recursiveUpdate($parentId, $parentIdField, [
                        $this->updateChildrenFields => $data[$this->updateChildrenFields]
                    ]);
                }
            }
        }
    }

    /**
     * 递归更新子级
     *
     * @time 2021年04月25日
     * @param $parentId
     * @param $parentIdField
     * @param $updateData
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return void
     */
    public function recursiveUpdate($parentId, $parentIdField, $updateData)
    {
        $this->where($parentIdField, $parentId)->update($updateData);

        $children = $this->where($parentIdField, $parentId)->select();

        if ($children->count()) {
            foreach ($children as $child) {
                $this->recursiveUpdate($child->id, $parentIdField, $updateData);
            }
        }
    }

  /**
   * 别名
   *
   * @time 2020年01月13日
   * @param $field
   * @param string $table
   * @return array|string
   */
    public function aliasField($field, $table = '')
    {
        $table = $table ? Utils::tableWithPrefix($table) : $this->getTable();

        if (is_string($field)) {
            return sprintf('%s.%s', $table, $field);
        }

        if (is_array($field)) {
            foreach ($field as &$value) {
                $value = sprintf('%s.%s', $table, $value);
            }

            return $field;
        }

        return $field;
    }

    /**
     * 禁用/启用
     *
     * @time 2020年06月29日
     * @param $id
     * @param string $field
     * @return mixed
     */
    public function disOrEnable($id, string $field='status')
    {
        $model = $this->findBy($id);

        $status = $model->{$field} == self::DISABLE ? self::ENABLE : self::DISABLE;

        $model->{$field} = $status;

        return $model->save();
    }

    /**
     * 过滤数据
     *
     * @time 2021年02月28日
     * @param array $data
     * @return mixed
     */
    protected function filterData(array $data)
    {
        foreach ($data as $field => $value) {
            if (is_null($value)) {
                unset($data[$field]);
            }

            if ($field == $this->getPk()) {
                unset($data[$field]);
            }
        }

        return $data;
    }


    /**
     * 模型导入
     *
     * @time 2021年04月28日
     * @param $fields
     * @param $file
     * @return bool
     */
    public function import($fields, $file): bool
    {
        $excel = new class(array_column($fields, 'field')) extends Reader {

            protected $fields;

            public function __construct($fields)
            {
                $this->fields = $fields;
            }

            public function headers()
            {
                // TODO: Implement headers() method.
                return $this->fields;
            }
        };

        $options = [];
        foreach ($fields as $field) {
            $p = [];
            if (isset($field['options']) && count($field['options'])) {
                foreach ($field['options'] as $op) {
                    $p[$op['label']] = $op['value'];
                }
                $options[$field['field']] = $p;
            }


        }

        $creatorId = request()->user()->id;

        $excel->import($file)->remove(0)->then(function ($data)  use ($options, $creatorId){
            foreach ($data as &$d) {
                foreach ($d as $field => &$v) {
                    if (isset($options[$field])) {
                        $v = $options[$field][$v];
                    }
                }

                $d['creator_id'] = $creatorId;

                $this->createBy($d);
            }
        });

        return true;
    }
}
