<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------


namespace catchAdmin\cms\model;

use catcher\exceptions\FailedException;

class ModelAuxiliaryTable extends BaseModel
{
    // 表名
    public $name = 'cms_model_auxiliary_table';
    // 数据库字段映射
    public $field = array(
        'id',
        // 模型ID
        'model_id',
        // 副表名称
        'table_name',
        // 默认使用
        'used',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );

    const USED = 1;
    const NOT_USE = 2;


    /**
     * 获取默认使用的副表
     *
     * @time 2021年03月08日
     * @param $modelId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|\think\Model|null
     */
    public static function getDefaultUsed(int $modelId)
    {
        return self::where('model_id', $modelId)
                    ->where('used', self::USED)
                    ->find();
    }

    /**
     * 默认使用
     *
     * @time 2021年03月08日
     * @param int $id
     * @return mixed
     */
    public function used(int $id)
    {
        $t = $this->findBy($id);

        $t->used = self::USED;

        if ($t->save()) {
            self::where('id', '<>', $id)
                    ->where('model_id', $t->model_id)
                    ->update([
                        'used' => self::NOT_USE,
                    ]);

            return $t;
        }

        throw new FailedException('启用失败');
    }

    /**
     * 获取使用
     *
     * @time 2021年03月08日
     * @param $modelId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|\think\Model|null
     */
    public function getUsed($modelId)
    {
        return $this->where('model_id', $modelId)
                    ->where('used', self::USED)
                    ->find();

    }
}