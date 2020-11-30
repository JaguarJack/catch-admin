<?php

namespace catchAdmin\trade\model;

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
// 数据库字段映射
class TradeConfig extends Model
{
    use BaseOptionsTrait, ScopeTrait;
    // 表名
    public $name = 'task_trade_config';
    public $field = array(
        'id',
        // 配置描述
        'remark',
        // 配置key
        'key',
        // 配置值
        'value',
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