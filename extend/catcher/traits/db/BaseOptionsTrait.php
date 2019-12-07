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
        foreach ($data as $field => $value) {
            if (in_array($field, $this->field)) {
                $this->{$field} = $value;
            }
        }

        if ($this->save()) {
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
        $model = $this->findBy($id);
        foreach ($data as $field => $value) {
            if (in_array($field, $this->field)) {
                $model->{$field} = $value;
            }
        }

        if ($model->save()) {
            return $model->id;
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
}
