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

class Tags extends BaseModel
{
    // 表名
    public $name = 'cms_tags';
    // 数据库字段映射
    public $field = array(
        'id',
        // 标签名称
        'name',
        // seo 标签
        'title',
        // 关键字
        'keywords',
        // 描述
        'description',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );


    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', $value);
    }
}