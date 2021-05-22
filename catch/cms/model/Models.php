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

use catchAdmin\cms\model\events\ModelsEvent;

class Models extends BaseModel
{
    use ModelsEvent;

    // 表名
    public $name = 'cms_models';
    // 数据库字段映射
    public $field = array(
        'id',
        // 模型名称
        'name',
        // 模型别名
        'alias',
        // 模型关联的表名,数据来源
        'table_name',
        // 模型描述
        'description',
        // 列表字段
        'used_at_list',
        // 搜索字段
        'used_at_search',
        // 详情字段
        'used_at_detail',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );


    /**
     * 模型字段
     *
     * @time 2021年05月11日
     * @return \think\model\relation\HasMany
     */
    public function fields(): \think\model\relation\HasMany
    {
        return $this->hasMany(ModelFields::class, 'model_id', 'id');
    }
}