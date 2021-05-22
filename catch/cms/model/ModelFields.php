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

use catchAdmin\cms\model\events\ModelFieldsEvent;
use catchAdmin\cms\support\Helper;
use catcher\Utils;

class ModelFields extends BaseModel
{
    use ModelFieldsEvent;

    // 表名
    public $name = 'cms_model_fields';
    // 数据库字段映射
    public $field = array(
        'id',
        // 字段中文名称
        'title',
        // 表单字段名称
        'name',
        // 类型
        'type',
        // 长度
        'length',
        // 默认值
        'default_value',
        'is_index', // 是否是索引
        'is_unique', // 是否是唯一
        'options', // 列表
        'rules', // 验证规则
        'pattern', // 字段正则
        // 模型ID
        'model_id',
        // 展示在列表 1 是 2 否
        'use_at_list',
        // 展示在详情 1 是 2 否
        'use_at_detail',
        // 用作是否搜索 1 是 2 否
        'use_at_search',
        // 创建人ID
        'creator_id',
        'sort',
        'status',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );


    const IS_UNIQUE = 1;
    const NOT_UNIQUE = 2;

    const IS_INDEX = 1;
    const NOT_INDEX = 2;

    /**
     * 获取模型的动态字段
     *
     * @time 2021年03月08日
     * @param $modelId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\Collection
     */
    public function getFieldsByModelId($modelId): \think\Collection
    {
        return $this->withoutField([
            'created_at', 'deleted_at', 'updated_at',
        ])->where('model_id', $modelId)->select();
    }

    /**
     * 获取规则
     *
     * @time 2021年03月07日
     * @param $value
     * @return string[]
     */
    public function getRulesAttr($value): array
    {
        return Utils::stringToArrayBy($value);
    }

    /**
     * 获取选项
     *
     * @time 2021年03月07日
     * @param $value
     * @return mixed
     */
    public function getOptionsAttr($value)
    {
        // return Helper::getOptions($value);
        return $value;
    }
}