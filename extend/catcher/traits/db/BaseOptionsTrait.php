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
            return $this->id;
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
            $model->id;
        }

        return false;
    }

    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @param array $field
     * @return mixed
     */
    public function findBy($id, array $field = ['*'])
    {
        return static::where($this->getPk(), $id)->select($field)->find();
    }

    /**
     *
     * @time 2019年12月03日
     * @param $id
     * @return mixed
     */
    public function deleteBy($id)
    {
        return static::destory($id);
    }
}
