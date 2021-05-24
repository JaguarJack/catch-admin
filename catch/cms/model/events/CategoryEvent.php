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

use catcher\exceptions\CatchException;
use catcher\exceptions\FailedException;
use catcher\Utils;

trait CategoryEvent
{
    /**
     * 插入前
     *
     * @time 2021年03月03日
     * @param \think\Model $category
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public static function onBeforeInsert(\think\Model $category): void
    {
        if (self::where('name', $category->getData('name'))->find()) {
            throw new FailedException('分类名称重复，请重新填写');
        }
    }

    /**
     * 更新前
     *
     * @time 2021年03月03日
     * @param \think\Model $category
     * @return mixed|void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public static function onBeforeUpdate(\think\Model $category)
    {
        $data = $category->getData();

        if (isset($data['name'])) {
            $where = $category->getWhere();

            if (self::where('name', $category->getData('name'))->where('id', '<>', $where['id'])->find()) {
                throw new FailedException('分类名称重复，请重新填写');
            }
        }
    }

    /**
     * 删除前
     *
     * @time 2021年03月03日
     * @param $category
     * @return void
     */
    public static function onBeforeDelete($category)
    {
        if ($category->hasNextLevel()) {
            throw new FailedException('存在下级栏目, 无法删除');
        }
    }
}