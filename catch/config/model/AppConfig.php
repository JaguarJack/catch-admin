<?php

namespace catchAdmin\config\model;

use catcher\base\CatchModel as Model;

// 数据库字段映射
class AppConfig extends Model
{
    // 表名
    public $name = 'task_app_config';
    public $field = array(
        'id',
        // 配置key
        'key',
        // 配置值
        'valve',
        // 配置描述
        'remark',
        // 是否必须 0 否 1是
        'force',
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
