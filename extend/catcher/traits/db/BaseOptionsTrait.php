<?php

namespace catcher\traits\db;

trait BaseOptionsTrait
{
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
     *
     * @time 2019年12月03日
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateBy($id, $data)
    {

        if (static::update($data, [$this->getPk() => $id], $this->field)) {
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
        return static::destroy($id, $force);
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
}
