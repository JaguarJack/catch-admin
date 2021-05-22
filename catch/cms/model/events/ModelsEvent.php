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

namespace catchAdmin\cms\model\events;

use catchAdmin\cms\support\Table;
use catcher\exceptions\FailedException;

trait ModelsEvent
{
    /**
     * 插入前
     *
     * @time 2021年03月03日
     * @param \think\Model $model
     * @return void
     */
    public static function onBeforeInsert($model): void
    {
        if (!Table::exist($model->getData('table_name'))) {
            throw new FailedException('模型关联的表【' .$model->getData('table_name'). '】不存在');
        }
    }

    /**
     * 更新前
     *
     * @time 2021年03月03日
     * @param \think\Model $model
     * @return void
     */
    public static function onBeforeUpdate($model): void
    {
        $data = $model->getData();

        $tableName = $data['table_name'] ?? null;

        if ($tableName && !Table::exist($model->getData('table_name'))) {
            throw new FailedException('模型关联的表【' .$model->getData('table_name'). '】不存在');
        }
    }
}
