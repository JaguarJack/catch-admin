<?php
declare(strict_types=1);

namespace catcher\traits\db;

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
  /**33
   *
   * @time 2019年12月03日
   * @param $id
   * @param $data
   * @param string $field
   * @return bool
   */
    public function updateBy($id, $data, $field = ''): bool
    {
        if (static::update($data, [$field ? : $this->getPk() => $id], $this->field)) {
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
    public function disOrEnable($id, $field='status')
    {
        $model = $this->findBy($id);

        $status = $model->{$field} == self::DISABLE ? self::ENABLE : self::DISABLE;

        $model->{$field} = $status;

        return $model->save();
    }
}
