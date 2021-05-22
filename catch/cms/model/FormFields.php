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

class FormFields extends BaseModel
{
    // 表名
    public $name = 'cms_form_fields';
    // 数据库字段映射
    public $field = array(
        'id',
        // form id
        'form_id',
        // 字段 label
        'label',
        // 表单字段name
        'name',
        // 默认值
        'default_value',
        // 类型
        'type',
        // 验证规则
        'rule',
        // 字段长度
        'length',
        // 验证失败信息
        'failed_message',
        // 1 展示 2 隐藏
        'status',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}